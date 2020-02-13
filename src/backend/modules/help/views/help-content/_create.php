<?php

use backend\modules\help\models\HelpContent;
use backend\modules\help\models\HelpSection;
use common\helpers\Lang;
use common\helpers\Url;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model HelpContent */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'module_id')->dropDownList(\backend\modules\help\models\HelpModules::getListData('id', 'name'),
    ['class' => 'select2']) ?>
<?= $form->field($model, 'user_level_id')->widget(\common\widgets\select2\Select2::class, ['name' => 'project',
    'value' => $model->user_level_id,
    'data' => \backend\modules\auth\models\UserLevels::getListData('id', 'name'),
    'options' => [
        'placeholder' => "---Select User Level---",
        'class' => 'form-control select2',
    ],
    'pluginOptions' => [
        'allowClear' => true
    ],]); ?>

<?= $form->field($model, 'name')->textInput() ?>

<?= $form->field($model, 'content')->widget(Widget::class, [
    'settings' => [
        'minHeight' => 300,
        'fileManagerJson' => Url::to(['/redactor/fetch-files']),
        'imageManagerJson' => Url::to(['/redactor/fetch-images']),
        'imageUpload' => Url::to(['/redactor/image-upload']),
        'fileUpload' => Url::to(['/redactor/file-upload']),
        'plugins' => [
            'clips',
            'fullscreen',
            'imagemanager',
            'filemanager'
        ],
    ],
]); ?>

<div class="kt-portlet__foot">
    <div class="kt-form__actions">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <button type="submit"
                        class="btn btn-success"><?= Lang::t($model->isNewRecord ? 'Create' : 'Save changes') ?></button>
                <a class="btn btn-secondary" href="<?= Url::getReturnUrl(Url::to(['index'])) ?>">
                    <?= Lang::t('Cancel') ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

