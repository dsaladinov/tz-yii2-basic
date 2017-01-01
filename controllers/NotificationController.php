<?php

namespace app\controllers;
use app\models\Notification;
use Yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\PageSize;

/**
 * Контроллер уведомлений юзера
 * Class NotificationController
 * @package app\controllers
 */
class NotificationController extends \yii\web\Controller
{
    /**
    * @return array Контроль доступа
    */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string Список всех уведомлений
     */
    public function actionIndex()
    {
        $model = Notification::find()->where([
            'user_id' => Yii::$app->user->identity->id,
            'notification_type_id' => 2
        ])
        ->orderBy(['id' => 'DESC']);

        $limit = PageSize::findOne(['id' => 1])->value;

        $pagination = new Pagination([
            'totalCount' => $model->count(),
            'pageSize' => $limit
        ]);

        $model
            ->limit($limit)
            ->offset(ArrayHelper::getValue($_GET, 'per-page', 0));

        $linkPager =  \yii\widgets\LinkPager::widget([
            'pagination' => $pagination,
        ]);

        return $this->render('index', [
            'model' => $model,
            'linkPager' => $linkPager
        ]);
    }

    /**
     * Просмотр увеодомления
     * @param $id Первичный_ключ
     * @return string
     */
    public function actionView($id)
    {
        $model = Notification::findOne(['id' => $id]);
        $model->read = 1;
        $model->save();

        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Удаление записи
     * @param $id Первичный_ключ
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        Notification::findOne(['id' => $id])->delete();
        return $this->redirect(ArrayHelper::getValue($_GET, 'returnUrl', Url::home()));
    }
}
