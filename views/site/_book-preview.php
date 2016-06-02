<?php
use yii\helpers\Html;
?>
<div class="book-preview col-xs-12 col-sm-6 col-md-4">
    <p><?= Html::a(
            Html::img('@web/uploads/' . $model->cover, ['class' => 'book-cover img-responsive']),
            ['site/view-book', 'id' => $model->id])
        ?></p>
    <h4><?= Html::a(Html::encode($model->title), ['site/view-book', 'id' => $model->id]) ?></h4>
</div>