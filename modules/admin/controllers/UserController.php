<?php

namespace app\modules\admin\controllers;

use app\models\Registration;
use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Контроллер Пользователь осуществляет действия CRUD для модели User.
 */
class UserController extends \app\modules\admin\controllers\DefaultController
{
    /**
     * @return array Контроль типов запросов и доступом ролей
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Списки всех моделей пользователей.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображение одной модели пользователя.
     * @param integer $id Первичный ключ
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создает новую модель пользователя.
     * Если создание прошло успешно, то браузер будет перенаправлен на страницу меню "Вид".
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Registration();

        if ($model->load(Yii::$app->request->post()) && $user_id = $model->registration()) {
            return $this->redirect(['view', 'id' => $user_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Разграничение прав
     * @param integer $id Первичный ключ
     * @return mixed
     */
    public function actionRoles($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->setRoles();
            Yii::$app->session->setFlash('success', 'Выполнено');
        }

        return $this->render('roles', [
            'model' => $model,
        ]);
    }

    /**
     * Изменить пароль
     * @param integer $id Первичный ключ
     * @return mixed
     */
    public function actionPass($id)
    {
        $model = $this->findModel($id);

        $model->action = 'pass';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->session->setFlash('success', 'Выполнено');
            $model->setNewPass();
        }

        return $this->render('pass', [
            'model' => $model,
        ]);
    }

    /**
     * Удалить существующую модель пользователя.
     * Если удаление прошло успешно, то браузер будет перенаправлен на страницу "индекс".
     * @param integer $id Первичный ключ
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Находит модель пользователя на основе его значения первичного ключа.
     * Если модель не найдена, исключение 404 HTTP будет брошен.
     * @param integer $id Первичный ключ
     * @return Пользователь загруженный модель
     * @throws NotFoundHttpException если модель не может быть найден
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }
}
