<?php

use yii\helpers\Inflector;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\core\models\Organization */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;

$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'] = [
    ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index', 'is_member' => $model->is_member, 'is_supplier' => $model->is_supplier, 'business_type' => $model->business_type]],
    $this->title
];

?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('forms/_form', ['model' => $model]) ?>
    </div>
</div>