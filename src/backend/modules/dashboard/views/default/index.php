<?php

use common\helpers\Lang;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;

$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'][] = ['label' => Lang::t('Dashboards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Member Dashboard', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>