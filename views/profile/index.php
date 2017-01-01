<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'НастроЙка профиля';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif ?>

<?php $form = ActiveForm::begin(); ?>

<ul>
    <li>
        <strong>Имя пользователя:</strong>
        <p><?= $user->username ?></p>
    </li>
    <li>
        <strong>E-mail:</strong>
        <p><?= $user->email ?></p>
    </li>
    <li>
        <?= $form->field($user, 'notificationTypes')->checkboxList($user->notificationTypesAll) ?>
    </li>
</ul>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
