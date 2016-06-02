<?php
use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = \Yii::t('app', 'Добавление книги');
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Книги'),
    'url' => ['admin/books'],
];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<p>
    <? $form = ActiveForm::begin([
        'id' => 'add-book-form',
    ])
    ?>
    <?= $form->field($book, 'title') ?>
    <?= $form->field($book, 'category_id')->dropDownList(Category::getCategoriesList(), ['prompt' => '']) ?>
    <?= $form->field($book, 'descr')->textarea() ?>
    <?= $form->field($book, 'cover')->fileInput() ?>
    <?= $form->field($book, 'file')->fileInput() ?>

<div class="form-group">
    <?= Html::submitButton(\Yii::t('app', 'Добавить'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(
        \Yii::t('app', 'Отмена'),
        Url::to(['admin/books']),
        ['class' => 'btn btn-default']
    ) ?>
</div>
<?php ActiveForm::end() ?>
</p>