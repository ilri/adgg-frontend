<?php

use common\helpers\Lang;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form ActiveForm */
/* @var $controller \backend\controllers\BackendController */
/* @var $model \backend\modules\core\models\AnimalBreedGroup */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();

$form = ActiveForm::begin([
    'id' => 'my-modal-form',
    'layout' => 'horizontal',
    'options' => ['class' => 'kt-form kt-form--label-right'],
    'fieldClass' => \common\forms\ActiveField::class,
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-md-3 col-form-label',
            'offset' => 'offset-md-3',
            'wrapper' => 'col-md-6',
            'error' => '',
            'hint' => '',
        ],
    ],
]);
?>
<div class="modal-header">
    <h5 class="modal-title"><?= Html::encode($this->title); ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="hidden" id="my-modal-notif"></div>
    <?= $form->field($model, 'name', []) ?>
    <?= $form->field($model, 'breeds')->widget(Select2::class, [
        'data' => \backend\modules\core\models\AnimalBreedGroup::getBreedsList(),
        'modal' => true,
        'options' => ['multiple' => true],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]) ?>
    <?= $form->field($model, 'color', [])->hint("Hex Color code (e.g #000000) to be used for data visualization. Breeds in the group will use this color as a base for their own colors automatically generated") ?>
    <?php if (!$model->isNewRecord): ?>
        <?= $form->field($model, 'is_active', [])->checkbox() ?>
    <?php endif; ?>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary">
        <?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?>
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<?php ActiveForm::end(); ?>
