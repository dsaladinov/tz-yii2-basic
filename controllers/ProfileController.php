<?php

namespace app\controllers;
use Yii;
use yii\filters\AccessControl;

/**
 * Настройка профиля
 * Class ProfileController
 * @package app\controllers
 */
class ProfileController extends \yii\web\Controller
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
     * Действия по регистрации
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;

        if($user->load(Yii::$app->request->post())) {
            $user->setNotificationTypes();
            Yii::$app->session->setFlash('success', 'Выполнено');
        }

        return $this->render('index', [
            'user' => $user
        ]);
    }

}
