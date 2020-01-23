<?php

use common\helpers\Lang;
use common\helpers\Url;

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
    <div class="col-md-6">
        <div class='card my-2'>
            <h4 class="card-header bg-white border-0">Tanzania</h4>
            <hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h1 class="text-center kt-font-info">34,369</h1>
                        <h6 class="text-center font-weight-normal">Number Of Farms</h6>
                        <h1 class="text-center kt-font-info">58,790</h1>
                        <h6 class="text-center font-weight-normal">Number Of Animals</h6>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card-footer bg-white d-flex justify-content-center justify-content-md-end border-0">
                            <a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space"
                               href="<?= Url::to(['/dashboard/stats/dash']) ?>">View Tanzania Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class='card my-2'>
            <h4 class="card-header bg-white border-0">Kenya</h4>
            <hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h1 class="text-center kt-font-info">0</h1>
                        <h6 class="text-center font-weight-normal">Number Of Farms</h6>
                        <h1 class="text-center kt-font-info">0</h1>
                        <h6 class="text-center font-weight-normal">Number Of Animals</h6>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card-footer bg-white d-flex justify-content-center justify-content-md-end border-0">
                            <a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space"
                               href="<?= Url::to(['/dashboard/stats/dash']) ?>">View
                                Kenya Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class='card my-2'>
            <h4 class="card-header bg-white border-0">Ethiopia</h4>
            <hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h1 class="text-center kt-font-info">0</h1>
                        <h6 class="text-center font-weight-normal">Number Of Farms</h6>
                        <h1 class="text-center kt-font-info">0</h1>
                        <h6 class="text-center font-weight-normal">Number Of Animals</h6>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card-footer bg-white d-flex justify-content-center justify-content-md-end border-0">
                            <a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space"
                               href="<?= Url::to(['/dashboard/stats/dash']) ?>">View Ethiopia Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-6">

    </div>
</div>