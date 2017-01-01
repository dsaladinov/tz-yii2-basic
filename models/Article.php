<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Модель Статьи
 * Class Article
 * @package app\models
 */
class Article extends \yii\db\ActiveRecord
{

//    Событие "после добавления статьи"
    const AFTER_INSERT_ARTICLE = 'AFTER_INSERT_ARTICLE';

    /**
     * @return string Назание таблицы
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @return array Результат валидации
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['title'], 'string', 'max' => 128],
            [['text', 'title'], 'required']
        ];
    }

    /**
     * @return array Заголовки полей
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'text' => 'Текст',
        ];
    }

    /**
     * Отправка уведомлений о вышедшей статье подписанным юзерам
     * @param $event
     * @throws HttpException
     */
    public function sendNotification($event)
    {
        $modelEvent = Event::findOne(['code' => $event->data['code']]);

        $template = NotificationTemplate::findOne(['event_id' => $modelEvent->id]);

        if($template->notificationTypes)
        {
            foreach($template->notificationTypes as $notificationType)
            {
                $query = new Query();

                $user_subscribed = $query
                    ->select('*')
                    ->from('user')
                    ->leftJoin('user_notification_type', 'user.id = user_notification_type.user_id')
                    ->where(['user_notification_type.notification_type_id' => $notificationType->id])
                    ->groupBy(['user.id'])
                    ->all();

                foreach ($user_subscribed as $user) {

                    $notification = new Notification;

                    $params = [
                        'sitename' => Yii::$app->id,
                        'username' => $user['username'],
                        'title' => $this->title,
                        'link' => Html::a('Ссылка', '/article/view?id='.$this->id)
                    ];

                    $notification->saveData([
                        'title' => NotificationTemplate::decode($template->title, $params),
                        'text' => NotificationTemplate::decode($template->text, $params),
                        'user_id' => $user['id'],
                        'user_email' => $user['email'],
                        'notification_type_id' => $notificationType->id
                    ]);
                }
            }
        }
    }
}
