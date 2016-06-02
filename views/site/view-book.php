<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $book->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row">
        <h1 class="col-xs-12"><?= $book->title ?></h1>
    </div>
    <div class="row">
        <div class="col-md-3">
            <img class="img-responsive" src="<?= Url::to('@web/uploads/' . $book->cover) ?>">
        </div>
        <div class="col-md-9">
            <p><strong><?= $book->getAttributeLabel('descr') ?></strong></p>
            <p><?= $book->descr ?></p>
            <p><strong><?= $book->getAttributeLabel('category_id') ?>:</strong>
                <?= Html::a(
                    $book->category->name,
                    Url::toRoute(['site/view-category', 'id' => $book->category->id])
                ) ?>
            </p>
            <p>
                <?= Html::a(
                    \Yii::t('app', 'Скачать'),
                    Url::toRoute(['site/download-book/', 'id' => $book->id]),
                    ['class' => 'btn btn-success']
                ) ?>
            </p>
        </div>
    </div>
</div>
