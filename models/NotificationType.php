<?php

namespace app\models;

use Yii;

/**
 * Это модель класса для таблицы "Тип уведомления".
 *
 * @property integer $id Первичный ключ
 * @property string $name Название
 *
 * Связи
 * @property Notification[] $notifications Уведомления
 * @property NotificationTemplateNotificationType[] $notificationTemplateNotificationTypes Смежная таблица между таблицой Шаблонов уведомлений
 * @property NotificationTemplate[] $notificationTemplates Шаблоны уведомлений
 * @property UserNotificationType[] $userNotificationTypes Смежная таблица между таблицей юзера
 * @property User[] $users Юзеры
 */
class NotificationType extends \yii\db\ActiveRecord
{
    /**
     * @return string Название таблицы
     */
    public static function tableName()
    {
        return 'notification_type';
    }

    /**
     * @return array Правила валидации
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 32],
            [['name'], 'required']
        ];
    }

    /**
     * @return array Заголовки полей
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название типа',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery Связанные уведомления
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['notification_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery Связанные шаблоны уведомлений
     */
    public function getNotificationTemplates()
    {
        return $this->hasMany(NotificationTemplate::className(), ['id' => 'notification_template_id'])->viaTable('notification_template_notification_type', ['notification_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery Связанные юзеры
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_notification_type', ['notification_type_id' => 'id']);
    }
}
