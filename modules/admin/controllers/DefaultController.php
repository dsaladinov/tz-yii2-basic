<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use Yii;
use yii\helpers\Url;
use yii\filters\AccessControl;

/**
 * Контроллер по умолчанию для `admin` модуля
 */
class DefaultController extends Controller
{
    /**
     * @var string Название шаблона видов
     */
    public $layout = 'main';

    /**
     * @return array Контроль типов запросов и доступов
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'moderator'],
                    ],
                ],
            ],
        ];
    }

    /**
     *
     * Перенаправляет на стр. входа, если юзер не авторизован
     *
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            $this->redirect(Yii::$app->user->loginUrl[0].'?returnURL='.URL::current());
        }
        else {
            return parent::beforeAction($action);
        }
    }

    /**
     *
     * Главная стр. админки
     *
     * @return string Вид гл. стр. админки
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
