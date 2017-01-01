<?php
/**
 * Created by PhpStorm.
 * User: ims-service
 * Date: 18.10.2016
 * Time: 12:01
 */
namespace app\commands;

use Yii;

use yii\console\controller;

class RbacController extends Controller
{
    /**
     * Создает роли и назначает разрешения для юзеров
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;


        $user = $auth->createRole('user');
        $auth->add($user);

        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->assign($admin, 1);
        $auth->assign($moderator, 2);
        $auth->assign($user, 3);
    }
}