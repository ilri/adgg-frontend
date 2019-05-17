<?php

use backend\modules\help\models\HelpContent;
use backend\modules\help\models\HelpSections;
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

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

