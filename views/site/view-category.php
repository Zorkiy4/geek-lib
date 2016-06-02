<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use app\components\TreeListWidget;

$this->title = $category->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= \Yii::t('app', 'Категория') ?>: <?= $this->title ?></h1>

<div class="row">
    <div class="col-md-4">
        <h2><?= \Yii::t('app', 'Подкатегории') ?></h2>
        <?= TreeListWidget::widget([
            'tree' => $category->getSubcategories(),
            'linked' => true,
        ]) ?>
    </div>
    <div class="col-md-8">
        <? if(count($category->books)): ?>
            <h2><?= \Yii::t('app', 'Книги') ?></h2>
            <?= ListView::widget([
                'dataProvider' => $books_provider,
                'itemView' => '_book-preview',
                'options' => [
                    'class' => 'row books-list'
                ],
            ]);?>
        <? else: ?>
            <p><?= \Yii::t('app', 'В данной категории нет ни одной книги.') ?></p>
        <? endif; ?>

    </div>
</div>