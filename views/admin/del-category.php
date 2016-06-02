<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = \Yii::t('app', 'Удаление категории');
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Категории'),
    'url' => ['admin/categories'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>
<p><?= \Yii::t(
        'app',
        'Вы уверены, что хотите удалить категорию "{name}" и все её подкатегории? Это действие будет невозможно отменить.',
        ['name' => $name]
    ) ?></p>
<div>
    <? $form = ActiveForm::begin([
        'id' => 'del-category-form',
    ]) ?>
    <?= Html::submitButton(\Yii::t('app', 'Удалить'), ['class' => 'btn btn-danger']) ?>
    <?= Html::a(
        \Yii::t('app', 'Отмена'),
        Url::to(['admin/categories']),
        ['class' => 'btn btn-default']
    ) ?>
    <?php ActiveForm::end() ?>
</div>