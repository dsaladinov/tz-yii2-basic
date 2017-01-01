<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PageSize */

$this->title = 'Create Page Size';
$this->params['breadcrumbs'][] = ['label' => 'Настройка кол-ва страниц', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-size-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
