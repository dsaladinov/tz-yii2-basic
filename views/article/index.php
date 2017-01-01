<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;

?>

<table class="table table-striped">
    <thead><th>Статьи</th></thead>
    <tbody>
    <?php foreach($model->all() as $item): ?>
        <tr>
            <td>
                <a href="<?= Url::home().'article/view?id='.$item->id ?>">
                    <?= Html::encode($item->title) ?>
                </a>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

<?= $linkPager ?>

