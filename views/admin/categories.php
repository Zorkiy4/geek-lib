<?
use app\models\Category;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = \Yii::t('app', 'Управление категориями');
$this->params['breadcrumbs'][] = $this->title;

$category = new Category();
?>
<style>
    .help-block {
        margin: 0;
    }

    .indent {
        float: left;
        height: 1.7em;
        margin: -0.4em 0.2em -0.4em -0.4em;
        padding: 0.42em 0 0.42em 0.6em;
        width: 20px;
    }
</style>

<h1><?= $this->title ?></h1>

<p>
    <? $form = ActiveForm::begin([
        'id' => 'add-category-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'offset' => 'col-sm-offset-1',
                'label' => 'col-sm-3',
                'wrapper' => 'col-sm-6',
                'error' => '',
                'hint' => 'col-sm-3',
            ],
        ],
    ]) ?>
    <?= $form->field($category, 'name') ?>
    <?= $form->field($category, 'parent')->dropDownList(\yii\helpers\ArrayHelper::map(Category::getCategories(), 'id', 'name'), ['prompt' => '']) ?>
<div class="form-group">
    <div class="col-lg-offset-3 col-lg-11">
        <?= Html::submitButton(\Yii::t('app', 'Добавить'), ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
</p>

<? if (count($categories)): ?>
    <table class="table">
        <tr>
            <th><?= \Yii::t('app', 'Название') ?></th>
            <th></th>
        </tr>
        <? foreach ($categories as $category): ?>
            <tr>
                <td>
                    <? for ($i = 0; $i < $category['indent']; $i++): ?>
                        <div class="indent">&nbsp;</div>
                    <? endfor; ?>
                    <?= $category['category']->name ?>
                </td>
                <td align="right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <?= \Yii::t('app', 'Действия') ?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?= Url::to(['admin/edit-category', 'id' => $category['category']->id]) ?>"><?= \Yii::t('app', 'Редактировать') ?></a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['admin/del-category', 'id' => $category['category']->id]) ?>"><?= \Yii::t('app', 'Удалить') ?></a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        <? endforeach; ?>
    </table>
<? else: ?>
    <p><?= \Yii::t('app', 'Ещё не создано ни одной категории.') ?></p>
<? endif; ?>
