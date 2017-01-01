<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= $model->title ?></h1>

<p>
    <?= $model->text ?>
</p>