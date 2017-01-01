<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Article;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use app\models\PageSize;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
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

}
