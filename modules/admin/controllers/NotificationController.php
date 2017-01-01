<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Notification;
use app\models\NotificationSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Уведомление Контроллер реализует действия CRUD для модели уведомления..
 */
class NotificationController extends DefaultController
{
    /**
     * @return array Контроль типов запросов и доступа
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
     * Перечень всех моделей уведомлений.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображает единую модель уведомления.
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
     * Создает новую модель уведомления.
     * Если создание прошло успешно, то браузер будет перенаправлен на страницу меню "Вид".
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Notification();

        if($model->load(Yii::$app->request->post())) {
            if($model->validate()) {
                $model->set();
                $this->redirect('success');
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @return string Вывод сообщение об успешно отправке
     */
    public function actionSuccess()
    {
        Yii::$app->session->setFlash('success', 'Отправка уведомления прошла успешно');
        return $this->render('success');
    }

    /**
     * Удалить существующую модель уведомления.
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
     * Находит модель уведомления на основе его значения первичного ключа.
     * Если модель не найдена, исключение 404 HTTP будет брошен.
     * @param integer $id Первичный ключ
     * @return Notification Загруженная модель
     * @throws NotFoundHttpException Если модель не может быть найден
     */
    protected function findModel($id)
    {
        if (($model = Notification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }
}
