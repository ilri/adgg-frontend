<?php

use backend\controllers\BackendController;
use backend\modules\core\models\Client;
use backend\modules\core\models\Country;
use backend\modules\core\models\Organization;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form ActiveForm */
/* @var $controller BackendController */
/* @var $model Client */
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
    <?= $form->field($model, 'description', [])->textarea() ?>

    <?= $form->field($model, 'country_id')->widget(Select2::class, [
        'data' => Country::getListData('id', 'name'),
        'options' => [
            'id' => Html::getInputId($model, 'country_id'),
            'class' => 'form-control parent-depdropdown',
            'placeholder' => '[select one]',
            'data-child-selectors' => [
                '#' . Html::getInputId($model, 'org_id'),
            ],
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]) ?>
    <?= $form->field($model, 'org_id')->widget(Select2::class, [
        'data' => Organization::getListData('id', 'name'),
        'options' => [
            'class' => 'form-control parent-depdropdown',
            'placeholder' => '[select one]',
            'data-url' => Url::to(['organization/get-list', 'country_id' => 'idV']),
            'data-selected' => $model->org_id,
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>
    <?php if (!$model->isNewRecord): ?>
        <?= $form->field($model, 'is_active', [])->checkbox() ?>
    <?php endif; ?>
    <?php foreach ($model->getAdditionalAttributes() as $attribute): ?>
        <?= $form->field($model, $attribute, []) ?>
    <?php endforeach; ?>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary">
        <?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?>
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<?php ActiveForm::end(); ?>
