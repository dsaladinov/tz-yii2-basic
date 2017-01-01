<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container">
        <?php
        NavBar::begin([
            'brandLabel' => 'Админка',
            'brandUrl' => URL::home().'admin',
            'options' => [
                'class' => '',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Публичная часть', 'url' => URL::home()],
                Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/login/out'], 'post', ['class' => 'navbar-form'])
                    . Html::submitButton(
                        'Выйти (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]);
        NavBar::end();
        ?>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="<?= Url::home().'admin/event'  ?>" class="list-group-item <?= Yii::$app->controller->id === 'event' ? 'active' : '' ?>">
                        События
                    </a>
                    <a href="<?= Url::home().'admin/notification-type'  ?>" class="list-group-item <?= Yii::$app->controller->id === 'notification-type' ? 'active' : '' ?>">
                        Типы уведомлений
                    </a>
                    <a href="<?= Url::home().'admin/notification-template'  ?>" class="list-group-item <?= Yii::$app->controller->id === 'notification-template' ? 'active' : '' ?>">
                        Шаблоны уведомлений
                    </a>
                    <a href="<?= Url::home().'admin/notification'  ?>" class="list-group-item <?= Yii::$app->controller->id === 'notification' ? 'active' : '' ?>">
                        Уведомления
                    </a>
                    <a href="<?= Url::home().'admin/user'  ?>" class="list-group-item <?= Yii::$app->controller->id === 'user' ? 'active' : '' ?>">
                        Пользователи
                    </a>
                    <a href="<?= Url::home().'admin/article'  ?>" class="list-group-item <?= Yii::$app->controller->id === 'article' ? 'active' : '' ?>">
                        Статьи
                    </a>
                    <a href="<?= Url::home().'admin/page-size'  ?>" class="list-group-item <?= Yii::$app->controller->id === 'page-size' ? 'active' : '' ?>">
                        Настройка пагинации
                    </a>
                </div>
            </div>
            <div class="col-md-9">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= $content ?>
            </div>
        </div>

    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
