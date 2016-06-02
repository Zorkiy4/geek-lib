<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = \Yii::t('app', 'Результаты поиска');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-4">
        <?= Html::beginForm(['site/search-results'], 'get', ['enctype' => 'multipart/form-data', 'class' => 'form-inline']) ?>
        <?= Html::input('text', 'search-text', '', ['class' => 'form-control']) ?>
        <?= Html::submitInput(\Yii::t('app', 'Поиск'), ['class' => 'btn btn-default']) ?>

        <?= Html::endForm() ?>
    </div>
    <div class="col-md-8">
        <h2><?= $this->title ?></h2>
        <? if(!is_null($books_provider)): ?>
            <?= ListView::widget([
                'dataProvider' => $books_provider,
                'itemView' => '_book-preview',
                'options' => [
                    'class' => 'books-list'
                ],
            ]);?>
        <? else: ?>
            <p><?= \Yii::t('app', 'По вашему запросу не найдено ни одной книги.') ?></p>
        <? endif; ?>

    </div>
</div>
