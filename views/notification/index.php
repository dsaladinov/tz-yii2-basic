<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Уведомления';
$this->params['breadcrumbs'][] = $this->title;

?>

<table class="table table-striped">
    <thead><th colspan="2">Уведомления</th></thead>
    <tbody>
        <?php foreach($model->all() as $item): ?>
            <tr>
                <td>
                    <a href="<?= Url::home().'notification/view?id='.$item->id ?>" style="font-weight: <?= $item->read ? 'normal' : 'bold' ?>">
                        <?= Html::encode($item->title) ?>
                    </a>
                </td>
                <td>
                    <a href="<?= Url::home().'notification/delete?id='.$item->id.'&returnUrl='.Yii::$app->request->url ?>" title="Удалить">
                        <i class="glyphicon glyphicon-remove"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?= $linkPager ?>

