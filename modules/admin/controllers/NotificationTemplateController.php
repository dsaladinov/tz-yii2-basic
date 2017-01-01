<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\NotificationTemplate;
use app\models\NotificationTemplateSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Шаблон оповещения Контроллер реализует действия CRUD для модели шаблона уведомлений.
 */
class NotificationTemplateController extends \app\modules\admin\controllers\DefaultController
{
    /**
     * @return array Контроль типов запросов и доступов ролей юзеров
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
     * Перечень всех моделей шаблона уведомлений.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificationTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Перечень всех моделей шаблона уведомлений.
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
     * Создает новую модель шаблона уведомлений.
     * Если создание успешно, то браузер будет перенаправлен на страницу меню "Вид".
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NotificationTemplate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Обновление существующей модели шаблона уведомлений.
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
                'model' => $model
            ]);
        }
    }

    /**
     * Удалить существующую модель шаблона уведомлений.
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
     * Находит модель шаблона уведомлений на основе его значения первичного ключа.
     * Если модель не найдена, исключение 404 HTTP будет брошен.
     * @param integer $id Первичный ключ
     * @return Шаблон оповещения загруженной модели
     * @throws NotFoundHttpException если модель не может быть найден
     */
    protected function findModel($id)
    {
        if (($model = NotificationTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }
}
