<?php

use backend\controllers\BackendController;
use backend\modules\core\models\FarmMetadataType;

/* @var $this \yii\web\View */
/* @var $searchModel FarmMetadataType */
/* @var $controller BackendController */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-lg-2">
        <?= $this->render('@confModule/views/layouts/_submenu'); ?>
    </div>
    <div class="col-lg-10">
        <h2>
            <?= $this->title ?>
        </h2>
        <?= $this->render('@coreModule/views/table-attribute/_tab'); ?>
        <div class="tab-content">
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>
