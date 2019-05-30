<?php

use common\widgets\fineuploader\Fineuploader;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $model \backend\modules\core\models\RegistrationDocument */
/* @var $this \yii\web\View */
$class_name = strtolower($model->shortClassName());
$fileAttribute = 'file_name';
$tmpFileAttribute = 'tmp_' . $fileAttribute;
$alertId = $fileAttribute . '_upload-alert';
?>
<div class="form-group row">
    <?= Html::activeLabel($model, $fileAttribute, ['class' => 'col-md-3 col-form-label']) ?>
    <div class="col-md-6">
        <?= Html::activeHiddenInput($model, $tmpFileAttribute) ?>
        <div>
            <?= Fineuploader::widget([
                'containerId' => 'fine-uploader-container-' . $fileAttribute,
                'index' => $fileAttribute,
                'buttonIcon' => 'fa fa-open',
                'buttonLabel' => 'Browse File',
                'fileType' => Fineuploader::FILE_TYPE_OTHERS,
                'fileSelector' => '#' . Html::getInputId($model, $tmpFileAttribute),
                'alertSelector' => '#' . $alertId,
                'options' => [
                    'request' => [
                        'endpoint' => Url::to(['/helper/upload-file']),
                        'params' => [Yii::$app->request->csrfParam => Yii::$app->request->csrfToken]
                    ],
                    'validation' => [
                        'allowedExtensions' => ['pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx', 'xls', 'xlsx', 'txt'],
                        'sizeLimit' => 50 * 1024 * 1024,
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
            <div id="<?= $alertId ?>"></div>
        </div>
    </div>
</div>