<?php

use backend\modules\core\models\Client;

/* @var $this \yii\web\View */
/* @var $searchModel Client */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;

$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('_filter', ['model' => $searchModel,]) ?>
        <?= $this->render('_grid', ['model' => $searchModel]) ?>
    </div>
</div>