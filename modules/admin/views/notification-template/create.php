<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\NotificationTemplate */

$this->title = 'Создать Шаблон уведомления';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны уведомлений', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
