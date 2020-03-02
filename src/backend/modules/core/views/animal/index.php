<?php

/* @var $this \yii\web\View */
/* @var $searchModel \backend\modules\core\models\Animal */
/* @var $country \backend\modules\core\models\Country */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;

$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@coreModule/views/animal/_indexTab', ['model' => $searchModel, 'country' => $country]) ?>
        <div class="tab-content">
            <?= $this->render('_filter', ['model' => $searchModel,]) ?>
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>