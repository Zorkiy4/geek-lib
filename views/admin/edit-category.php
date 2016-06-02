<?php
use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = \Yii::t('app', 'Редактирование категории');
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Категории'),
    'url' => ['admin/categories'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>

<div>
    <? $form = ActiveForm::begin([
        'id' => 'edit-category-form',
    ]) ?>
    <?= $form->field($category, 'name') ?>
    <?= $form->field($category, 'parent')->dropDownList(\yii\helpers\ArrayHelper::map(Category::getCategories(), 'id', 'name'), ['prompt' => '']) ?>
    <?= Html::submitButton(\Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(
        \Yii::t('app', 'Отмена'),
        Url::to(['admin/categories']),
        ['class' => 'btn btn-default']
    ) ?>
    <?php ActiveForm::end() ?>
</div>