<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Это модель класса для таблицы "уведомления"
 *
 * @property integer $id Первичный ключ
 * @property string $title Заголовок
 * @property string $text Текст
 * @property integer $user_id Ключ юзера
 * @property integer $notification_type_id Тип уведомления
 * @property integer $read Статус "прочитано"
 *
 * Связи
 * @property NotificationType $notificationType Типы уведомлений
 * @property User $user Юзеры
 */
class Notification extends \yii\db\ActiveRecord
{

    /**
     * @return string Название таблицы
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @return array Правила валидации
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['notification_type_id', 'read'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['notification_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationType::className(), 'targetAttribute' => ['notification_type_id' => 'id']],
//            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['title', 'text', 'user_id', 'notification_type_id'], 'required']
        ];
    }

    /**
     * @return array Заголовки Атрибутов
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'read' => 'Прочитано',
            'notification_type_id' => 'Тип уведомления',
            'user_id' => 'Пользователь'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery Связанный тип уведомления
     */
    public function getNotificationType()
    {
        return $this->hasOne(NotificationType::className(), ['id' => 'notification_type_id']);
    }

    /**
     * @return array Вывод всех типов уведомлений
     */
    public function getNotificationTypes()
    {
        $model = NotificationType::find()->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery Связанный юзер
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return array Вывод всех юзеров
     */
    public function getUsers()
    {
        $query = new Query();

        $users = $query
            ->select('*')
            ->from('user')
            ->leftJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->where(['auth_assignment.item_name' => 'user'])
            ->all();

        return ArrayHelper::map($users, 'id', 'username');
    }

    /**
     * Передача данных для отправки уведомлений
     */
    public function set()
    {
        $users = User::findAll(['id' => (array) $this->user_id]);
        foreach($users as $user) {
            $data = [
                'title' => $this->title,
                'text' => $this->text,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'notification_type_id' => $this->notification_type_id
            ];

            $this->saveData($data);
        }

    }

    /**
     * Отправка уведомлений
     * @param $data - Входящие данные
     */
    public function saveData($data)
    {
        $count = UserNotificationType::find()
            ->where([
                'user_id' => $data['user_id'],
                'notification_type_id' => $data['notification_type_id']
            ])
            ->count();

//        если юзер подписан
        if($count) {

            $notification = new Notification();

//            сохранение уведомления в БД
            $notification->title = $data['title'];
            $notification->text = $data['text'];
            $notification->user_id = $data['user_id'];
            $notification->notification_type_id = $data['notification_type_id'];
            $notification->save();

//            Отправка уведомления на почту
            if($data['notification_type_id'] == 1)
            {
                Yii::$app->mailer->compose()
                    ->setFrom('infosales@openspace.kz')
                    ->setTo($data['user_email'])
                    ->setSubject($data['title'])
                    ->setHtmlBody($data['text'])
                    ->send();
            }
        }
    }
}
