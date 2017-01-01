<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\db\Query;
use yii\web\HttpException;

class User extends ActiveRecord implements IdentityInterface
{

//    Событие "после смены пароля юзера админом"
    const AFTER_CHANGE_PASS_USER = 'after_change_pass_user';

//    Событие "после регистрации/добавления юзера"
    const AFTER_ADD_USER = 'after_add_user';

//    Событие "блокировки юзера админом"
    const AFTER_BLOCK_USER = 'after_block_user';

    /**
     * @var Новый_пароль
     */
    public $new_pass;

    /**
     * @var Подтверждение_нового_пароля
     */
    public $new_pass_confirm;

    /**
     * @var Подтверждение_пароля
     */
    public $pass_confirm;

    /**
     * @var Экшн
     */
    public $action;

    /**
     * @return Название_таблицы
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return array Правила валидации
     */
    public function rules()
    {
        return [
            [['new_pass', 'new_pass_confirm'], 'required', 'when' => function($model){
                return $model->action === 'pass';
            }, 'whenClient' => "function (attribute, value) {
                    return $('#action').val() == 'pass';
            }"],
            ['new_pass', 'string', 'min' => 6], // пароль должен состоять из не менее 6 символов
            ['new_pass_confirm', 'newPassConfirmValidate'],
        ];
    }

    /**
     * @return array Заголовки Атрибутов
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'email' => 'Эл. почта',
            'roles' => 'Роли',
            'new_pass' => 'Новый пароль',
            'new_pass_confirm' => 'Подтверждение нового пароля',
            'notificationTypes' => 'Подписка по типам уведомлений'
        ];
    }

    /**
     * Проверка подтверждения пароля на совпадение c паролем
     * @param $attribute Название_поля
     */
    public function newPassConfirmValidate($attribute)
    {
        if($this->$attribute !== $this->new_pass) {
            $this->addError($attribute, 'Подтверждение пароля не совпадает с новым паролем');
        }
    }

    /**
     * @param $password Параметр_пароль
     * @return bool Результат проверки пароля на соответствие
     */
    public function passwordValidate($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @return \yii\db\ActiveQuery Связанные события типа Web
     */
    public function getNewNotifications()
    {
        return $this->hasMany(Notification::className(), ['user_id' => 'id'])
            ->where([
                'notification_type_id' => 2,
                'read' => null
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery Связанные типы уведомлений
     */
    public function getNotificationTypes()
    {
        return $this
            ->hasMany(NotificationType::className(), ['id' => 'notification_type_id'])
            ->viaTable('user_notification_type', ['user_id' => 'id']);
    }

    /**
     * @return array Вывод всех типов уведомлений
     */
    public function getNotificationTypesAll()
    {
        $model = NotificationType::find()->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * Сохранение типов уведомлений в юзере
     */
    public function setNotificationTypes()
    {
        $notificationTypes = Yii::$app->request->post()['User']['notificationTypes'];

        $this->unlinkAll('notificationTypes', true);

        if($notificationTypes) {
            foreach ($notificationTypes as $notificationType) {
                $this->link('notificationTypes', NotificationType::findOne(['id' => $notificationType]));
            }
        }
    }

    /**
     * @return $this Связанные роли
     */
    public function getRoles()
    {
        return $this
            ->hasMany(AuthItem::className(), ['name' => 'item_name'])
            ->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * @return array Список всех ролей
     */
    public function getRolesAll()
    {
        $model = AuthItem::find()->all();
        return ArrayHelper::map($model, 'name', 'name');
    }

    /**
     * Сохранение ролей/Блокировка
     */
    public function setRoles()
    {
        $roles = Yii::$app->request->post()['User']['roles'];

        $this->unlinkAll('roles', true);

        if($roles) {
            foreach ($roles as $role) {
                $this->link('roles', AuthItem::findOne(['name' => $role]));
            }
        }
        else {
            $this->on(User::AFTER_BLOCK_USER, [$this, 'sendNotification'], [
                'code' => User::AFTER_BLOCK_USER,
                'user_id' => $this->id,
                'user_email' => $this->email,
                'params' => [
                    'sitename' => Yii::$app->id,
                    'username' => $this->username
                ]
            ]);

            $this->trigger(User::AFTER_BLOCK_USER);
        }
    }

    /**
     * Смена пароля
     * @throws \yii\base\Exception
     */
    public function setNewPass()
    {
        $this->password = Yii::$app->security->generatePasswordHash($this->new_pass);
        $this->save();

        $this->on(User::AFTER_CHANGE_PASS_USER, [$this, 'sendNotification'], [
            'code' => User::AFTER_CHANGE_PASS_USER,
            'user_id' => $this->id,
            'user_email' => $this->email,
            'params' => [
                'sitename' => Yii::$app->id,
                'username' => $this->username,
                'newPass' => $this->new_pass
            ]
        ]);

        $this->trigger(User::AFTER_CHANGE_PASS_USER);
    }

    /**
     * Отправка уведомлений по шаблону юзеру и админу
     * @param $event
     * @throws HttpException
     */
    public function sendNotification($event)
    {
        $modelEvent = Event::findOne(['code' => $event->data['code']]);

//        Шаблон  уведомленрия для юзера
        $template = NotificationTemplate::findOne([
            'event_id' => $modelEvent->id,
            'duty' => null
        ]);

        if($template->notificationTypes)
        {
            foreach($template->notificationTypes as $notificationType)
            {
                $notification = new Notification;
                $notification->saveData([
                    'title' => NotificationTemplate::decode($template->title, $event->data['params']),
                    'text' => NotificationTemplate::decode($template->text, $event->data['params']),
                    'user_id' => $event->data['user_id'],
                    'user_email' => $event->data['user_email'],
                    'notification_type_id' => $notificationType->id
                ]);
            }
        }

//        Шаблон  уведомления для админа
        $template = NotificationTemplate::findOne([
            'event_id' => $modelEvent->id,
            'duty' => 1
        ]);

        if($template->notificationTypes)
        {
            foreach($template->notificationTypes as $notificationType)
            {
                foreach(User::getUsersByRole('admin') as $admin)
                {
                    $notification = new Notification;
                    $notification->saveData([
                        'title' => NotificationTemplate::decode($template->title, $event->data['params']),
                        'text' => NotificationTemplate::decode($template->text, $event->data['params']),
                        'user_id' => $admin['id'],
                        'user_email' => $admin['email'],
                        'notification_type_id' => $notificationType->id
                    ]);
                }
            }
        }
    }

    /**
     * @param $role Название_роли
     * @return array Юзера с данной ролью
     */
    public static function getUsersByRole($role)
    {
        $query = new Query();

        return $query->select('*')
            ->from('auth_assignment')
            ->leftJoin('user', 'auth_assignment.user_id = user.id')
            ->where(['auth_assignment.item_name' => $role])
            ->all();
    }


    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}