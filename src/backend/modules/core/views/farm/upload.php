<?php

use common\helpers\Lang;
use common\helpers\Utils;
use yii\helpers\Inflector;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\core\forms\UploadFarms */
/* @var $controller \backend\controllers\BackendController */

$controller = Yii::$app->controller;
$this->title = Lang::t('Upload {resource}', ['resource' => Inflector::pluralize($controller->resourceLabel)]);
$this->params['breadcrumbs'][] = ['label' => Utils::pluralize($controller->resourceLabel), 'url' => array_merge(['index'], Yii::$app->request->queryParams)];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@coreModule/views/farm/_uploadTab', []) ?>
        <div class="tab-content">
            <?= $this->render('_uploadForm', ['model' => $model]) ?>
        </div>
    </div>
</div>