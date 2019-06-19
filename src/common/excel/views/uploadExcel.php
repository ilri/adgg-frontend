<?php
/* @var $model \common\models\ActiveRecord */
/* @var $form_id string */

/* @var $previewUrl string */

use common\widgets\fineuploader\Fineuploader;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Url;

$label_size = isset($label_size) ? $label_size : 2;
$input_size = isset($input_name) ? $input_name : 10;
$label_class = 'col-md-' . $label_size;
$input_class = 'col-md-' . $input_size;
$offset_class = 'offset-md-' . $label_size;
?>

<div class="form-group row">
    <?= Html::activeLabel($model, 'file', ['class' => $label_class . ' control-label']) ?>
    <div class="<?= $input_class ?>">
        <?= Html::activeHiddenInput($model, 'file'); ?>
        <div>
            <?= Fineuploader::widget([
                'buttonIcon' => 'fas fa-open',
                'buttonLabel' => 'Browse File (Excel or CSV)',
                'fileType' => Fineuploader::FILE_TYPE_EXCEL,
                'fileSelector' => '#' . Html::getInputId($model, 'file'),
                'alertSelector' => '#file-progress-notif',
                'excelSheetSelector' => '#' . Html::getInputId($model, 'sheet'),
                'options' => [
                    'request' => [
                        'endpoint' => Url::to(['/helper/upload-file', 'excel' => true]),
                        'params' => [Yii::$app->request->csrfParam => Yii::$app->request->csrfToken]
                    ],
                    'validation' => [
                        'allowedExtensions' => ['csv', 'xls', 'xlsx'],
                        'sizeLimit' => 30 * 1024 * 1024,
                    ],
                    'deleteFile' => [
                        'enabled' => true,
                        'method' => 'POST',
                        'endpoint' => Url::to(['/helper/delete-upload']),
                        'params' => [Yii::$app->request->csrfParam => Yii::$app->request->csrfToken],
                    ],
                    'classes' => [
                        'success' => 'alert alert-success',
                        'fail' => 'alert alert-error'
                    ],
                    'multiple' => false,
                    'debug' => false,
                ]
            ]) ?>
            <?= Html::error($model, 'file') ?>

            <div class="checkbox">
                <label>
                    <?= Html::checkbox('excel-skip-first-row', true, ['id' => 'excel-skip-first-row']) ?> Skip the first
                    row (If the first row is column names, start reading data from the 2nd row.)
                </label>
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <?= Html::activeLabel($model, 'sheet', ['label' => Lang::t('Sheet:'), 'class' => $label_class . ' control-label']) ?>
    <div class="<?= $input_class ?>">
        <?= Html::activeDropDownList($model, 'sheet', [], ['class' => '']) ?>
    </div>
</div>

<div class="row">
    <div class="<?= $offset_class . ' ' . $input_class ?>">
        <div class="accordion" id="accordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h6>
                        <a role="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                           aria-controls="collapseOne">
                            <i class="fa fa-chevron-down"></i> <?= Lang::t('Advanced Excel Options') ?>:
                        </a>
                    </h6>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-body">
                        <fieldset>
                            <p>Get Data From:</p>
                            <div class="form-group row">
                                <label class="<?= $label_class ?> col-form-label"><?= Lang::t('Row:') ?></label>

                                <div class="col-md-3">
                                    <?= Html::activeTextInput($model, 'start_row', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('start_row')]) ?>
                                </div>

                                <div class="col-md-2">
                                    <label class="col-form-label"><?= Lang::t('To') ?></label>
                                </div>

                                <div class="col-md-3">
                                    <?= Html::activeTextInput($model, 'end_row', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('end_row')]) ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="<?= $label_class ?> col-form-label"><?= Lang::t('Column:') ?></label>

                                <div class="col-md-3">
                                    <?= Html::activeTextInput($model, 'start_column', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('start_column'), 'maxlength' => 1]) ?>
                                </div>

                                <div class="col-md-2">
                                    <label class="col-form-label"><?= Lang::t('To') ?></label>
                                </div>

                                <div class="col-md-3">
                                    <?= Html::activeTextInput($model, 'end_column', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('end_column'), 'maxlength' => 1]) ?>
                                </div>
                            </div>
                        </fieldset>
                        <div id="placeholder_columns" class="form-group padding-10 hidden"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
$options = [
    'form' => $form_id,
    'previewUrl' => $previewUrl,
    'excel' => [
        'sheetSelector' => '#' . Html::getInputId($model, 'sheet'),
        'startRowSelector' => '#' . Html::getInputId($model, 'start_row'),
        'endRowSelector' => '#' . Html::getInputId($model, 'end_row'),
        'startColumnSelector' => '#' . Html::getInputId($model, 'start_column'),
        'endColumnSelector' => '#' . Html::getInputId($model, 'end_column'),
        'skipFirstRowSelector' => '#excel-skip-first-row',
    ],
];

$this->registerJs("MyApp.plugin.importExcel(" . \yii\helpers\Json::encode($options) . ");");
?>
