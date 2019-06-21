<?php

use common\helpers\Lang;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
?>

<div class="row">
    <div class="col-md-12">
        <?= $this->render('graph/_graph', ['graphFilterOptions' => $graphFilterOptions]) ?>
    </div>
</div>