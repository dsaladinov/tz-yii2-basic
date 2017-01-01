<?php

namespace app\controllers;

use Yii;
use \yii\web\Controller;
use \app\models\Login;
use \yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * Контроллер авторизации
 * Class LoginController
 * @package app\controllers
 */
class LoginController extends Controller
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
                        'actions' => ['out'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Экшн авторизации
     */
    public function actionIndex()
    {
        $model = new Login;

        $returnURL = ArrayHelper::getValue($_GET, 'returnURL', null);

        if($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack($returnURL);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * @return \yii\web\Response Выход
     */
    public function actionOut()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}