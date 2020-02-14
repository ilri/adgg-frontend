<?php

use backend\modules\auth\models\Resources;
use backend\modules\help\models\HelpModules;
use common\helpers\Lang;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model HelpModules */
/* @var $form yii\widgets\ActiveForm */

$this->title = $this->context->pageTitle;

$form = ActiveForm::begin([
    'id' => 'my-modal-form',
    'layout' => 'horizontal',
    'options' => ['data-model' => strtolower($model->shortClassName())],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-md-3',
            'offset' => 'col-md-offset-3',
            'wrapper' => 'col-md-6',
            'error' => '',
            'hint' => '',
        ],
    ],
]);
?>
<div class="modal-header">
    <h4 class="modal-title"><?= Html::encode($this->title); ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
</div>

<div class="modal-body">
    <div class="alert hidden" id="my-modal-notif"></div>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'resource_name')->dropDownList(Resources::getListData(), ['class' => 'select2']) ?>

</div>
<div class="modal-footer">
    <button class="btn btn-primary" type="submit">
        <i class="fa fa-check"></i> <?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?>
    </button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
        <i class="fa fa-times"></i> <?= Lang::t('Close') ?>
    </button>
</div>
<?php ActiveForm::end(); ?>
