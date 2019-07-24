<?php

use backend\modules\auth\Session;
use backend\modules\core\models\Organization;
use common\forms\ActiveField;
use common\widgets\select2\Select2;
use yii\bootstrap\Html;
use common\helpers\Url;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\auth\forms\UploadUsers */
/* @var $orgModel Organization */
/* @var $form ActiveForm */
?>
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title"><?= Html::encode($this->title) ?></h3>
        </div>
    </div>
    <?php
    $formId = 'upload-users-form';
    $form = ActiveForm::begin([
        'id' => $formId,
        'layout' => 'horizontal',
        'options' => ['class' => 'kt-form kt-form--label-right'],
        'fieldClass' => ActiveField::class,
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-md-2 col-form-label',
                'offset' => 'offset-md-2',
                'wrapper' => 'col-md-10',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]);
    ?>
    <div class="kt-portlet__body">
        <?= Html::errorSummary($model, ['class' => 'alert alert-warning', 'header' => '']); ?>
        <div class="kt-section kt-section--first">
            <div class="kt-section__body">
                <div class="row">
                    <div class="col-md-6">
                        <?php if (!Session::isOrganization()): ?>
                            <?= $form->field($model, 'org_id')->widget(Select2::class, [
                                'data' => Organization::getListData(),
                                'options' => ['placeholder' => '[select one]'],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]) ?>
                        <?php endif; ?>
                        <?= $form->field($model, 'role_id')->widget(Select2::class, [
                            'data' => \backend\modules\auth\models\Roles::getListData('id', 'name', false, ['level_id' => $model->level_id]),
                            'options' => ['placeholder' => '[select one]'],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]) ?>
                        <?= $this->render('@common/excel/views/uploadExcel', ['model' => $model, 'form_id' => $formId, 'previewUrl' => Url::to(['upload-preview', 'org_id' => $model->org_id, 'level_id' => $model->level_id])]); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->render('@common/excel/views/guide', ['model' => $model, 'sampleUrl' => Url::to(['/helper/download-excel-sample', 'route' => 'users.xlsx']),]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-md-8 offset-md-1">
                    <button type="submit" class="btn btn-success">
                        <?= Lang::t('Upload') ?>
                    </button>
                    <a class="btn btn-secondary"
                       href="<?= Url::getReturnUrl(Url::to(['index', 'org_id' => $model->org_id, 'level_id' => $model->level_id])) ?>">
                        <?= Lang::t('Cancel') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>