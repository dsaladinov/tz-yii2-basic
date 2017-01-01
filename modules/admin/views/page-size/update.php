<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PageSize */

$this->title = 'Обновить Page Size: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Настройка кол-ва страниц', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="page-size-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
