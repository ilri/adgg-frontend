<?php

use backend\modules\core\models\FarmMetadataType;
use backend\modules\core\models\Country;
use common\widgets\select2\Select2;
use common\forms\ActiveField;
use yii\bootstrap\Html;
use common\helpers\Url;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\core\forms\UploadFarmMetadata */
/* @var $metadataTypeParentModel FarmMetadataType */
/* @var $form ActiveForm */
$code = Yii::$app->request->get('type');
$country_id = Yii::$app->request->get('country_id', null);
$hasChildren = !empty($metadataTypeParentModel->children);
?>
<div class="row">
    <?php if ($hasChildren): ?>
        <div class="col-lg-2">
            <div class="kt-portlet">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <ul class="kt-nav kt-nav--bold kt-nav--md-space kt-nav--v3 kt-margin-t-20 kt-margin-b-20 nav nav-tabs"
                        role="tablist">
                        <li class="kt-nav__item">
                            <a class="kt-nav__link<?= ($code == $metadataTypeParentModel->code) ? ' active' : '' ?>"
                               href="<?= Url::to(['upload-metadata', 'country_id' => $country_id, 'type' => $metadataTypeParentModel->code]) ?>">
                                <span class="kt-nav__link-text"><?= Lang::t('Upload {parent}', ['parent' => $metadataTypeParentModel->name]) ?></span>
                            </a>
                        </li>
                        <?php
                        foreach ($metadataTypeParentModel->children as $child):?>
                            <li class="kt-nav__item">
                                <a class="kt-nav__link<?= ($code == $child->code) ? ' active' : '' ?>"
                                   href="<?= Url::to(['upload-metadata', 'country_id' => $country_id, 'type' => $child->code]) ?>">
                                    <span class="kt-nav__link-text"><?= Lang::t('Upload {child}', ['child' => $child->name]) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="<?= $hasChildren ? 'col-lg-10' : 'col-lg-12'; ?>">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title"><?= Html::encode($this->title) ?></h3>
                </div>
            </div>
            <?php
            $formId = 'upload-farm-metadata-form';
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
                            <div class="col-md-8">
                                <?= $form->field($model, 'country_id')->widget(Select2::class, [
                                    'data' => Country::getListData(),
                                    'options' => ['placeholder' => '[select one]'],
                                    'pluginOptions' => [
                                        'allowClear' => false
                                    ],
                                ]) ?>
                                <?= $this->render('@common/excel/views/uploadExcel', ['model' => $model, 'form_id' => $formId, 'previewUrl' => Url::to(['upload-metadata-preview', 'type' => Yii::$app->request->get('type')])]); ?>
                            </div>
                            <div class="col-md-4">
                                <?= $this->render('@common/excel/views/guide', ['model' => $model, 'sampleUrl' => Url::to(['/helper/download-excel-sample', 'route' => 'farm.xlsx']),]); ?>
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
                               href="<?= Url::getReturnUrl(Url::to(['index', 'country_id' => Yii::$app->request->get('country_id')])) ?>">
                                <?= Lang::t('Cancel') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
