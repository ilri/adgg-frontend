<?php

use common\helpers\Lang;
use common\helpers\Utils;
use yii\helpers\Inflector;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\auth\forms\UploadUsers */
/* @var $controller \backend\controllers\BackendController */

$controller = Yii::$app->controller;
$this->title = Lang::t('Upload {resource}', ['resource' => Inflector::pluralize($controller->resourceLabel)]);
$this->params['breadcrumbs'][] = ['label' => Utils::pluralize($controller->resourceLabel), 'url' => ['index', 'country_id' => $model->country_id, 'level_id' => $model->level_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <?= $this->render('_uploadForm', ['model' => $model]) ?>
    </div>
</div>