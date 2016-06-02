<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = \Yii::t('app', 'Перемещение книг');
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Книги'),
    'url' => ['admin/books'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>
<p><?= \Yii::t(
        'app',
        'Вы уверены, что хотите переместить книги?'
    ) ?></p>
<div>
    <? $form = ActiveForm::begin([
        'id' => 'del-book-form',
    ]) ?>
    <?= Html::submitButton(\Yii::t('app', 'Удалить'), ['class' => 'btn btn-danger']) ?>
    <?= Html::a(
        \Yii::t('app', 'Отмена'),
        Url::to(['admin/books']),
        ['class' => 'btn btn-default']
    ) ?>
    <?php ActiveForm::end() ?>
</div>
