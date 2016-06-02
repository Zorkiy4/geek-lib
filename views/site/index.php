<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\widgets\ListView;
use app\components\TreeListWidget;

$this->title = 'Geek Library';
?>
<div class="site-index">

    <div class="body-content">
        <div class="row">

        </div>

        <div class="row">
            <div class="col-md-4">
                <?= Html::beginForm(['site/search-results'], 'get', ['enctype' => 'multipart/form-data', 'class' => 'form-inline']) ?>
                    <?= Html::input('text', 'search-text', '', ['class' => 'form-control']) ?>
                    <?= Html::submitInput(\Yii::t('app', 'Поиск'), ['class' => 'btn btn-default']) ?>

                <?= Html::endForm() ?>
                <h2><?= \Yii::t('app', 'Список категорий') ?></h2>
                <?= TreeListWidget::widget([
                    'tree' => $categories_tree,
                    'linked' => true,
                    'count' => true,
                ]) ?>
            </div>
            <div class="col-md-8">
                <h1><?= \Yii::t('app', 'Список книг') ?> <?= !empty($idx)?Yii::t('app', 'на букву {idx}', ['idx' => $idx]):''; ?></h1>
                <p>
                    <? foreach (range(chr(0xC0), chr(0xDF)) as $ltr): ?>
                        <?= Html::a(
                            iconv('CP1251', 'UTF-8', $ltr),
                            Url::to(['site/index', 'idx' => iconv('CP1251', 'UTF-8', $ltr)])
                        );
                        ?>
                    <? endforeach; ?>
                </p>
                <?= ListView::widget([
                    'dataProvider' => $books_provider,
                    'itemView' => '_book-preview',
                    'itemOptions' => [
                        'class' => 'book-item'
                    ],
                    'options' => [
                        'class' => 'books-list'
                    ],
/*                    'pager' => [
                        'prevPageCssClass' => 'my-z4-class',
                        'options' => [
                            'class' => 'pagination clearfix'
                        ],
                    ],*/
                ]);?>
            </div>
        </div>
    </div>
</div>
