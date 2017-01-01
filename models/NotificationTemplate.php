<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Это модель класса для таблицы "шаблона уведомления".
 *
 * @property integer $id Первичный ключ
 * @property integer $event_id ID События
 * @property string $title Заголовок
 * @property string $text Текст
 *
 * Связи:
 * @property Event $event Атрибут события
 * @property NotificationTemplateNotificationType[] $notificationTemplateNotificationTypes Смежная таблица между типом уведомлений
 * @property NotificationType[] $notificationTypes Тип уведомления
 */
class NotificationTemplate extends \yii\db\ActiveRecord
{

    /**
     * @return string Название таблицы
     */
    public static function tableName()
    {
        return 'notification_template';
    }

    /**
     * @return array Правила валидации
     */
    public function rules()
    {
        return [
            [['event_id'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 128],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
            [['event_id', 'title', 'text'], 'required'],
        ];
    }

    /**
     * @return array Заголовки атрибутов
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'ID События',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'notificationTypes' => 'Типы уведомлений'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery Связанное событие
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * @return array Список всех событий
     */
    public function getEvents()
    {
        $model = Event::find()->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery Связанные типы уведомлений
     */
    public function getNotificationTypes()
    {
        return $this->hasMany(NotificationType::className(), ['id' => 'notification_type_id'])->viaTable('notification_template_notification_type', ['notification_template_id' => 'id']);
    }

    /**
     * @return array Список всех типов уведомлений
     */
    public function getNotificationTypesAll()
    {
        $model = NotificationType::find()->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

    /**
     * Сохранение типов уведомлений
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $post = Yii::$app->request->post();
        $notification_types = NotificationType::findAll(['id' => $post['NotificationTemplate']['notificationTypes']]);

        $this->unlinkAll('notificationTypes', true);

        foreach ($notification_types as $notification_type) {
            $this->link('notificationTypes', $notification_type);
        }
    }

    /**
     * Декодирование параметров вставки
     * @param $string Входящяя_строка
     * @param $data Параметры
     * @return mixed Строка с замененными параметрами
     */
    public static function decode($string, $data)
    {
        preg_match_all('|{(.+?)}|is', $string, $matches);

        for($i = 0; $i < count($matches[1]); $i++) {
            $string = str_replace($matches[0][$i], $data[str_replace(['{', '}'], '', $matches[0][$i])], $string);
        }

        return $string;
    }
}
