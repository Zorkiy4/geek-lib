<?php
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;


$this->title = \Yii::t('app', 'Управление книгами');
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<p>
    <?= Html::a(
        \Yii::t('app', 'Добавить книгу'),
        Url::to(['admin/add-book']),
        ['class' => 'btn btn-default']
    ) ?>
</p>

<?= Html::beginForm(['admin/move-books'], 'post', ['enctype' => 'multipart/form-data', 'class' => 'form-inline']) ?>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        'title',
        [
            'attribute' => 'category.name',
            'label' => \Yii::t('app', 'Категория'),
        ],
        [
            'label' => \Yii::t('app', 'Обложка'),
            'value' => function ($book) {
                return Html::a(
                    $book->cover,
                    URL::to('@web/uploads/' . $book->cover)
                );
            },
            'format' => 'html',
        ],
        [
            'label' => \Yii::t('app', 'Файл'),
            'value' => function ($book) {
                return Html::a(
                    $book->file,
                    URL::to('@web/uploads/' . $book->file)
                );
            },
            'format' => 'html',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'urlCreator' => function ($url, $model, $key) {
                switch ($url) {
                    case 'view':
                        return Url::to(['site/view-book', 'id' => $key]);
                    case 'update':
                        return Url::to(['admin/edit-book', 'id' => $key]);
                    case 'delete':
                        return Url::to(['admin/del-book', 'id' => $key]);
                }
            },
        ],
    ],
]);
?>
<?= Html::label(\Yii::t('app', 'Переместить выбранное в'), 'categories', ['class' => 'control-label']) ?>
&nbsp;
<?= Html::dropDownList('category', '', \app\models\Category::getCategoriesList(), ['class' => 'form-control']) ?>
&nbsp;
<?= Html::submitInput(\Yii::t('app', 'Выполнить'), ['class' => 'btn btn-default']) ?>
<?= Html::endForm() ?>
