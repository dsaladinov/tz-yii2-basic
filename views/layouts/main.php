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
                'brandLabel' => 'Тестовый новостной сайт',
                'brandUrl' => URL::home(),
            ]);

            $items = [];

            array_push($items, [
                'label' => 'АДМИН ПАНЕЛЬ', 'url' => URL::home().'admin'
            ]);

            if(Yii::$app->user->isGuest) {
                array_push($items,
                    ['label' => 'Вход', 'url' => URL::home().'login'],
                    ['label' => 'Регистрация', 'url' => URL::home().'registration']
                );
            }
            else {
                array_push($items,[
                    'label' => 'Выйти ('.Yii::$app->user->identity->username.')', 'url' => URL::home().'login/out'
                ]);
            }

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $items
            ]);

            NavBar::end();
        ?>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="<?= Url::home().'article'  ?>" class="list-group-item <?= Yii::$app->controller->id === 'article' ||  Yii::$app->controller->id === 'site' ? 'active' : '' ?>">
                        Статьи
                    </a>
                    <a href="<?= Url::home().'profile'  ?>" class="list-group-item <?= Yii::$app->controller->id === 'profile' ? 'active' : '' ?>">
                        Настройка профиля
                    </a>
                    <a href="<?= Url::home().'notification'  ?>" class="list-group-item <?= Yii::$app->controller->id === 'notification' ? 'active' : '' ?>">
                        <?php if( ! Yii::$app->user->isGuest): ?>
                            <?php if($count = count(Yii::$app->user->identity->newNotifications)): ?>
                                <span class="badge"><?= $count ?></span>
                            <?php endif; ?>
                        <?php endif ?>
                        Уведомления
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
