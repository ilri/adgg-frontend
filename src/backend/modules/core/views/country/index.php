<?php

/* @var $this yii\web\View */
/* @var $searchModel \backend\modules\core\models\Country */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;

//$this->title = $controller->getPageTitle();
$this->title = 'Manage Country List';
$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('_grid', ['model' => $searchModel]) ?>
    </div>
</div>