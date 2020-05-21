<?php

use backend\modules\auth\Session;
use backend\modules\core\models\Country;
use backend\modules\core\models\FarmMetadataType;
use common\forms\ActiveField;
use common\widgets\select2\Select2;
use yii\bootstrap\Html;
use common\helpers\Url;
use common\helpers\Lang;
use yii\bootstrap4\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\core\forms\UploadFarmMetadata */
/* @var $parentMetadataModel FarmMetadataType*/
/* @var $form ActiveForm */
$code = Yii::$app->request->get('type');
?>
<?php
$childrenTypes= FarmMetadataType::getData(['code','name'],['parent_id'=>$parentMetadataModel->code]);
?>
<div class="row">
    <div class="col-lg-2">
        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <ul class="kt-nav kt-nav--bold kt-nav--md-space kt-nav--v3 kt-margin-t-20 kt-margin-b-20 nav nav-tabs "
                    role="tablist">
                    <li class="kt-nav__item">
                        <a class="kt-nav__link<?= ($code == $parentMetadataModel->code) ? ' active' : '' ?>"
                           href="<?= Url::to(['upload-metadata','country_id'=>$model->country_id, 'type'=>$parentMetadataModel->code]) ?>">
                            <span class="kt-nav__link-text"><?= Lang::t('Upload {parent}',['parent'=>$parentMetadataModel->name]) ?></span>
                        </a>
                    </li>
                    <?php
                    foreach ($childrenTypes as $childType) {
                        $type= $childType['code'];
                        $name= $childType['name'];
                        ?>
                        <li class="kt-nav__item">
                            <a class="kt-nav__link<?= ($code == $type) ? ' active' : '' ?>"
                               href="<?= Url::to(['upload-metadata','country_id'=>$model->country_id, 'type'=>$type]) ?>">
                                <span class="kt-nav__link-text"><?= Lang::t('Upload {child}',['child'=>$name]) ?></span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>    </div>
    <div class="col-lg-10">
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
                                <?php if (!Session::isCountry()): ?>
                                    <?= $form->field($model, 'country_id')->widget(Select2::class, [
                                        'data' => Country::getListData(),
                                        'options' => ['placeholder' => '[select one]'],
                                        'pluginOptions' => [
                                            'allowClear' => false
                                        ],
                                    ]) ?>
                                <?php endif; ?>
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
                               href="<?= Url::getReturnUrl(Url::to(['index', 'country_id' => $model->country_id])) ?>">
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
