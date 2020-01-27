<?php
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \backend\modules\conf\models\AndroidApps */

$this->title = $this->context->pageTitle;

$form = ActiveForm::begin([
    'id' => 'my-modal-form',
    'layout' => 'horizontal',
    'enableClientValidation' => false,
    'options' => ['data-model' => strtolower($model->shortClassName())],
    'fieldConfig' => [
        'enableError' => false,
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
<div class="modal-header bg-gray-200">
    <h4 class="modal-title"><?= Html::encode($this->title); ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        <span aria-hidden="true"><i class="fal fa-times"></i></span>
    </button>
</div>

<div class="modal-body">
    <div class="alert hidden" id="my-modal-notif"></div>

    <?= $form->field($model, 'version_code', [])->hint('e.g 1.1'); ?>

    <?= $form->field($model, 'version_name', [])->hint('e.g Release 1.1 Production'); ?>

    <?= $this->render('@app/modules/conf/views/android-app/_apkField', ['model' => $model]) ?>

    <?= $form->field($model, 'is_active', [])->checkbox(['class' => 'checkbox']); ?>
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
