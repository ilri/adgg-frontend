<?php

use backend\modules\core\models\Organization;
use backend\modules\core\models\RegistrationDocumentType;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $controller \backend\controllers\BackendController */
/* @var $model RegistrationDocumentType */
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
            'wrapper' => 'col-md-8',
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
    <?= $form->field($model, 'business_types')->widget(Select2::class, [
        'data' => Organization::businessTypeOptions(),
        'modal' => true,
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => ['multiple' => true],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]) ?>
    <?= $form->field($model, 'business_entity_types')->widget(Select2::class, [
        'data' => Organization::businessEntityTypeOptions(),
        'modal' => true,
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => ['multiple' => true],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]) ?>
    <?php //$form->field($model, 'has_start_date', [])->checkbox() ?>
    <?php //$form->field($model, 'has_renewal', [])->checkbox() ?>
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
