<?php

use common\helpers\Lang;
use common\helpers\Utils;
use yii\helpers\Inflector;

/* @var $this \yii\web\View */
/* @var $orgModel \backend\modules\core\models\Organization */
/* @var $model \backend\modules\core\forms\UploadOrganizationUnits */
/* @var $controller \backend\controllers\BackendController */

$controller = Yii::$app->controller;
$this->title = Lang::t('Upload {resource}', ['resource' => Inflector::pluralize($controller->resourceLabel)]);
$this->params['breadcrumbs'][] = ['label' => Utils::pluralize($controller->resourceLabel), 'url' => ['index', 'org_id' => $orgModel->uuid, 'level' => $model->level]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <?= $this->render('_uploadForm', ['model' => $model,'orgModel'=>$orgModel]) ?>
    </div>
</div>