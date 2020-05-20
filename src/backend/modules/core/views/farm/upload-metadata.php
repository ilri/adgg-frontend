<?php

use common\helpers\Lang;
use common\helpers\Utils;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\core\forms\UploadFarmMetadata */
/* @var $controller \backend\controllers\BackendController */
/* @var $metadataTypeModel \backend\modules\core\models\FarmMetadataType*/

$controller = Yii::$app->controller;
$this->title = Lang::t('Upload {resource}', ['resource' => Html::encode($metadataTypeModel->name)]);
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