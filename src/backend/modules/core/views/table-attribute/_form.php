<?php

use backend\modules\core\models\TableAttribute;
use backend\modules\core\models\TableAttributesGroup;
use common\helpers\Lang;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $form ActiveForm */
/* @var $controller \backend\controllers\BackendController */
/* @var $model TableAttribute */
$controller = Yii::$app->controller;
$this->title = Lang::t('Create {table} {resource}', ['table' => TableAttribute::decodeTableId($model->table_id), 'resource' => $controller->resourceLabel]);

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
    <?php if ($model->table_id == TableAttribute::TABLE_ANIMAL_EVENTS): ?>
        <?= $form->field($model, 'event_type')->widget(Select2::class, [
            'data' => \backend\modules\core\models\AnimalEvent::eventTypeOptions(false),
            'modal' => true,
            'options' => ['placeholder' => '[select one]'],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]) ?>
    <?php endif ?>
    <?php if ($model->table_id == TableAttribute::TABLE_FARM_METADATA): ?>
        <?= $form->field($model, 'farm_metadata_type')->widget(Select2::class, [
            'data' => \backend\modules\core\models\FarmMetadataType::getListData('code', 'name', false),
            'modal' => true,
            'options' => ['placeholder' => '[select one]'],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]) ?>
    <?php endif ?>
    <?= $form->field($model, 'attribute_key', []) ?>
    <?= $form->field($model, 'attribute_label', []) ?>
    <?= \common\widgets\smartSelect\SmartSelect::widget([
        'model' => $model,
        'optionsModel' => TableAttributesGroup::class,
        'attribute' => 'group_id',
        'inputAttribute' => 'name',
        'selectData' => TableAttributesGroup::getListData('id', 'name', true, ['table_id' => $model->table_id]),
        'labelOptions' => ['class' => 'col-form-label col-md-3'],
        'fieldWrapperOptions' => ['class' => 'col-md-6'],
        'selectOptions' => ['class' => 'form-control'],
        'url' => Url::to(['table-attributes-group/quick-create', 'table_id' => $model->table_id]),
        'showAddLink' => true,
    ]) ?>
    <?= $form->field($model, 'input_type')->widget(Select2::class, [
        'data' => TableAttribute::inputTypeOptions(),
        'modal' => true,
        'options' => [
            'placeholder' => '[select one]',
            'data-show-list-type' => [TableAttribute::INPUT_TYPE_SELECT, TableAttribute::INPUT_TYPE_MULTI_SELECT],
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]) ?>
    <?= $form->field($model, 'list_type_id')->widget(Select2::class, [
        'data' => \backend\modules\core\models\ChoiceTypes::getListData(),
        'modal' => true,
        'options' => ['placeholder' => '[select one]'],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]) ?>
    <?= $form->field($model, 'default_value', []) ?>
    <?php /* echo $form->field($model, 'is_alias', [])->checkbox()*/ ?>
    <?php /*echo $form->field($model, 'alias_to')->widget(Select2::class, [
        'data' => $model->getAliasToList(),
        'modal' => true,
        'options' => ['placeholder' => '[select one]'],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ])*/ ?>
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
<?php
$options = [
    'inputTypeFieldSelector' => '#' . Html::getInputId($model, 'input_type'),
    'listTypeIdFieldSelector' => '#' . Html::getInputId($model, 'list_type_id'),
];
$this->registerJs("MyApp.modules.core.initTableAttributesForm(" . Json::encode($options) . ");");
?>
