<?php

use yii\helpers\Inflector;

/* @var $this \yii\web\View */
/* @var $model \backend\modules\core\models\Animal */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;

$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'] = [
    ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index', 'country_id' => $model->country_id]],
    $this->title
];

?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>
