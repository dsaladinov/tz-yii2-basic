<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать Пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'email:email',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete} {roles} {pass}',
                'buttons' => [
                    'roles' => function ($url) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-user"></span>',
                            $url,
                            [
                                'title' => 'Разграничение прав',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                    'pass' => function ($url) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-lock"></span>',
                            $url,
                            [
                                'title' => 'Изменить пароль',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                ],
                'visibleButtons' => [
                    'update' => false,
                ],

            ],
        ],
    ]); ?>
</div>
