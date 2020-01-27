<?php
use common\widgets\fineuploader\Fineuploader;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\modules\conf\models\AndroidApps */
/* @var $this \yii\web\View */

$class_name = strtolower($model->shortClassName());
$notif_id = 'upload-notif';
?>
<div class="form-group">
    <?= \yii\bootstrap4\Html::activeLabel($model, 'apk_file', ['class' => 'control-label col-md-3']) ?>
    <div class="col-md-8">
        <?= Html::activeHiddenInput($model, 'temp_apk_file') ?>
        <div>
            <?= Fineuploader::widget([
                'buttonIcon' => 'fa fa-open',
                'buttonLabel' => 'Browse Files',
                'fileType' => Fineuploader::FILE_TYPE_OTHERS,
                'fileSelector' => '#' . $class_name . '-temp_apk_file',
                'alertSelector' => '#file-progress-notif',
                'options' => [
                    'request' => [
                        'endpoint' => Url::to(['/helper/upload-file']),
                        'params' => [Yii::$app->request->csrfParam => Yii::$app->request->csrfToken]
                    ],
                    'validation' => [
                        'allowedExtensions' => ['apk'],
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
            <div id="<?= $notif_id ?>"></div>
        </div>
    </div>
</div>