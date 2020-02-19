<?php

use backend\modules\core\models\OrganizationRefUnits;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form ActiveForm */
/* @var $controller \backend\controllers\BackendController */
/* @var $model OrganizationRefUnits */
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
    <?= $form->field($model, 'code', []) ?>
    <?= $form->field($model, 'name', []) ?>
    <?php if ($model->level > OrganizationRefUnits::LEVEL_REGION): ?>
        <?= $form->field($model, 'parent_id')->widget(Select2::class, [
            'data' => OrganizationRefUnits::getListData('id', 'name', false, ['level' => $model->level - 1, 'country_id' => $model->country_id]),
            'modal' => true,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]) ?>
    <?php endif; ?>
    <?= $form->field($model, 'contact_name', []) ?>
    <?= $form->field($model, 'contact_phone', []) ?>
    <?= $form->field($model, 'contact_email', []) ?>
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
