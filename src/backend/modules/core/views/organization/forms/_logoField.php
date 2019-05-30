<?php

use common\widgets\fineuploader\Fineuploader;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $model \backend\modules\core\models\Organization */
/* @var $this \yii\web\View */

$class_name = strtolower($model->shortClassName());
$fileAttribute = 'logo';
$tmpFileAttribute = 'tmp_' . $fileAttribute;
$alertId = $fileAttribute . '_upload-alert';

?>
<div class="form-group row">
    <?= Html::activeLabel($model, $tmpFileAttribute, ['class' => 'col-md-2 col-form-label']) ?>
    <div class="col-md-8">
        <?= Html::activeHiddenInput($model, $tmpFileAttribute) ?>
        <div>
            <?= Fineuploader::widget([
                'buttonIcon' => 'fa fa-open',
                'buttonLabel' => 'Browse Image',
                'fileType' => Fineuploader::FILE_TYPE_IMAGE,
                'fileSelector' => '#' . Html::getInputId($model, $tmpFileAttribute),
                'alertSelector' => '#' . $alertId,
                'options' => [
                    'request' => [
                        'endpoint' => Url::to(['/helper/upload-file']),
                        'params' => [Yii::$app->request->csrfParam => Yii::$app->request->csrfToken]
                    ],
                    'validation' => [
                        'allowedExtensions' => ['jpeg', 'jpg', 'png'],
                        'sizeLimit' => 5 * 1024 * 1024,
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