<?php

use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model \backend\modules\conf\models\NumberingFormat */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = $controller->pageTitle;

$form = ActiveForm::begin([
    'id' => 'my-modal-form',
    'layout' => 'horizontal',
    'options' => ['class' => 'kt-form kt-form--label-right'],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-md-3',
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
    <?php if (\backend\modules\auth\Session::isDev()): ?>
        <?= $form->field($model, 'code', []); ?>
        <?= $form->field($model, 'name', []); ?>
        <?= $form->field($model, 'is_private', [])->checkbox(); ?>
    <?php endif; ?>
    <?= $form->field($model, 'next_number', [])->textInput(['class' => 'form-control update-preview']); ?>
    <?= $form->field($model, 'min_digits', [])->textInput(['class' => 'form-control update-preview']); ?>
    <?= $form->field($model, 'prefix', [])->textInput(['class' => 'form-control update-preview']); ?>
    <?= $form->field($model, 'suffix', [])->textInput(['class' => 'form-control update-preview']); ?>
    <?= $form->field($model, 'preview', [])->textInput(['class' => 'form-control update-preview', 'readonly' => true]); ?>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-primary">
        <?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?>
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<?php ActiveForm::end(); ?>

<?php
$options = [];
$this->registerJs("MyApp.modules.conf.numberingFormat(" . Json::encode($options) . ");");
?>
