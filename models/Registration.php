<?php
/**
 *
 * Модель Регистрации
 */
namespace app\models;

use Codeception\Lib\Notification;
use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\HttpException;

class Registration extends Model {

    /**
     * @var Имя_пользователя
     */
    public $username;

    /**
     * @var Эл_почта
     */
    public $email;

    /**
     * @var Пароль
     */
    public $password;

    /**
     * @var Подтверждение_пароля
     */
    public $password_confirm;

    /**
     * @return array Заголовки полей
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Эл. почта',
            'password' => 'Пароль',
            'password_confirm' => 'Подтверждение пароля',
        ];
    }

    /**
     * @return array Валидация входящих данных
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'password_confirm'], 'required'], // имя пользователя, эл. почта, пароль и подверждение пароля не может быть пустым
            ['email', 'email'], // email valid
            [['username', 'email'], 'trim'], // удаление пробелов username, email
            [['username', 'email'], 'string', 'max' => 32], // username и email максимум 32 сивсволов
            [['password'], 'string', 'min' => 6], // пароль должен состоять из не менее 6 символов
            [['username', 'email'], 'uniqueValidate'], // Проверка на уникальность username/email
            ['password_confirm', 'passwordConfirmValidate'] // Проверка подтверждения пароля на совпадение c паролем
        ];
    }

    /**
     *
     * Проверка на уникальность username/email
     *
     * @param $attribute Название_поля
     */
    public function uniqueValidate($attribute)
    {
        $user = User::findOne([$attribute => $this->$attribute]);

        if($user) {
            if($user->id !== null) {
                $this->addError($attribute, 'Пользователь с таким '.$attribute.' уже есть в базе.');
            }
        }
    }

    /**
     *
     * Проверка подтверждения пароля на совпадение c паролем
     *
     * @param $attribute Название_поля
     */
    public function passwordConfirmValidate($attribute)
    {
        if($this->$attribute !== $this->password) {
            $this->addError($attribute, 'Подтверждение пароля не совпадает с паролем');
        }
    }

    /**
     * Регистрация юзера
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function registration()
    {
        if($this->validate())  {
            $user = new User;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
            $user->activation_hash = Yii::$app->security->generateRandomString();
            $user->save();

            foreach(NotificationType::find()->all() as $item) {
                $user->link('notificationTypes', $item);
            }

            $user->on(User::AFTER_ADD_USER, [$user, 'sendNotification'], [
                'code' => User::AFTER_ADD_USER,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'params' => [
                    'sitename' => Yii::$app->id,
                    'username' => $user->username,
                    'password' => $this->password,
                    'linkToUser' => Html::a('Ссылка', Url::home(true).'admin/user/view?id='.$user->id),
                    'linkActivation' => Html::a('Ссылка', Url::home(true).'registration/activation?hash='.$user->activation_hash)
                ]
            ]);

            $user->trigger(User::AFTER_ADD_USER);

            return $user->id;
        }
    }

    /**
     * @param $hash Хеш_активации
     * @return bool Результат активации
     * @throws HttpException
     */
    public function activation($hash)
    {
        $user = User::findOne(['activation_hash' => $hash]);

        if( ! $user) {
            throw new HttpException('500', 'User not found');
        }

        if( ! array_key_exists('user', Yii::$app->authManager->getRolesByUser($user->id))) {
            $user->link('roles', AuthItem::findOne(['name' => 'user']));
        }

        $user->activation_hash = null;
        $user->save();

        return $user;
    }


}