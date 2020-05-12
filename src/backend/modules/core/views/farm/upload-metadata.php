<?php

use backend\modules\core\models\FarmMetadata;
use common\helpers\Lang;
use common\helpers\Utils;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\core\forms\UploadFarmMetadata */
/* @var $controller \backend\controllers\BackendController */

$controller = Yii::$app->controller;
$type = Yii::$app->request->get('type', FarmMetadata::TYPE_FEEDING_SYSTEMS_METADATA);
$this->title = Lang::t('Upload {resource}', ['resource' => FarmMetadata::decodeType($type)]);
$this->params['breadcrumbs'][] = ['label' => Utils::pluralize($controller->resourceLabel), 'url' => array_merge(['index'], Yii::$app->request->queryParams)];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@coreModule/views/farm/_uploadTab', []) ?>
        <div class="tab-content">
            <?= $this->render('_uploadMetadataForm', ['model' => $model]) ?>
        </div>
    </div>
</div>