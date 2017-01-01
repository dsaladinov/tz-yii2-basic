<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\NotificationType;
use app\models\NotificationTypeSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Тип уведомления Контроллер реализует действия CRUD для модели типа уведомления.
 */
class NotificationTypeController extends \app\modules\admin\controllers\DefaultController
{
    /**
     * Контроль типов запросов и доступа по ролям юзеров
     * @return array
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
                        'roles' => ['admin', 'moderator'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Перечень всех моделей типа уведомления.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificationTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображение одной модели типа уведомления.
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
     * Создает новую модель типа уведомления.
     * Если создание прошло успешно, то браузер будет перенаправлен на страницу меню "Вид".
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NotificationType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Обновление существующей модели типа уведомления.
     * Если обновление прошло успешно, то браузер будет перенаправлен на страницу меню "Вид".
     * @param integer $id Первичный ключ
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удалить существующую модель типа уведомления.
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
     * Находит модель типа уведомления на основе его значения первичного ключа.
     * Если модель не найдена, исключение 404 HTTP будет брошен.
     * @param integer $id Первичный ключ
     * @return NotificationType загруженная модель
     * @throws NotFoundHttpException если модель не может быть найдена
     */
    protected function findModel($id)
    {
        if (($model = NotificationType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }
}
