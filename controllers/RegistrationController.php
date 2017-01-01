<?php
/**
 * 
 * Контроллер регистрации юзера
 */
namespace app\controllers;

use Yii;
use \yii\web\Controller;
use \app\models\Registration;
use \app\models\Login;

use \yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\filters\AccessControl;

/**
 *
 * Контроллер дял работы с регистрацией
 *
 * Class RegistrationController
 * @package app\controllers
 */
class RegistrationController extends Controller
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
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Возвращает вид и принимает входящие данные для регистрации
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $model = new Registration;

        if($model->load(Yii::$app->request->post()) && $model->registration()) {
            return $this->redirect(URL::home().'registration/success');
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * @return string Выводит сообщение об успешной регистрации
     */
    public function actionSuccess()
    {
        Yii::$app->session->setFlash('success', 'Регистрация прошла успешно. Проверьте почту, чтобы подтвердить аккаунт');
        return $this->render('success');
    }

    /**
     * Делает активацию юзера
     */
    public function actionActivation()
    {
        $model = new Registration;

        $hash = ArrayHelper::getValue($_GET, 'hash', false);

        $user = $model->activation($hash);

        $login = new Login();
        $login->username = $user->username;
        $login->login(true);

        return $this->redirect(URL::home());
    }

}