<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Event;
use app\models\EventSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Событие контроллера реализует действия CRUD для модели событий.
 */
class EventController extends \app\modules\admin\controllers\DefaultController
{
    /**
     * Фильтр запросов и контроль доступа
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
     * Перечень всех моделей событий.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображение одной модели событий.
     * @param integer $id Ключ_события
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создает новую модель событий.
     * Если создание прошло успешно, то браузер будет перенаправлен на страницу меню "Вид".
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Event();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Обновление существующей модели событий.
     * Если обновление прошло успешно, то браузер будет перенаправлен на страницу меню "Вид".
     * @param integer $id Ключ События
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
     * Удалить существующую модель событий.
     * Если удаление прошло успешно, то браузер будет перенаправлен на страницу "индекс".
     * @param integer $id Ключ события
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionParams()
    {
        $id = (int) Yii::$app->request->post('id');
        $model = Event::findOne(['id' => $id]);
        echo json_encode($model->params);
    }

    /**
     * Находит модель событий, основанную на его значение первичного ключа.
     * Если модель не найдена, исключение 404 HTTP будет брошен.
     * @param integer $id Ключ события
     * @return Событие нагруженная модель
     * @throws NotFoundHttpException если модель не может быть найден
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }

}
