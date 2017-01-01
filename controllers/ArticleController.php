<?php

namespace app\controllers;

use app\models\Article;
use app\models\PageSize;
use Yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Контроллер статей
 * Class NotificationController
 * @package app\controllers
 */
class ArticleController extends \yii\web\Controller
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
                        'actions' => ['index'],
                        'roles' => ['?'],
                    ],
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
        $model = Article::find()->orderBy(['id' => 'DESC']);

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
        $model = Article::findOne(['id' => $id]);

        return $this->render('view', [
            'model' => $model
        ]);
    }
}
