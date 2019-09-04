<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
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
    <div class="col-md-3">
        <div class="card" style="width: 18rem;">
            <div class="card-header">
                Animal Stats
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Added Today
                    <strong><?= Animal::getDashboardStats(Animal::STATS_TODAY) ?></strong></li>
                <li class="list-group-item">Added This Week
                    <strong><?= Animal::getDashboardStats(Animal::STATS_THIS_WEEK) ?></strong></li>
                <li class="list-group-item">Added This Month
                    <strong><?= Animal::getDashboardStats(Animal::STATS_THIS_MONTH) ?></strong></li>
            </ul>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="width: 18rem;">
            <div class="card-header">
                Farm Stats
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    Added Today
                    <strong><?= Farm::getDashboardStats(Farm::STATS_TODAY) ?></strong>
                </li>
                <li class="list-group-item">
                    Added This Week
                    <strong><?= Farm::getDashboardStats(Farm::STATS_THIS_WEEK) ?></strong>
                </li>
                <li class="list-group-item">
                    Added This Month
                    <strong><?= Farm::getDashboardStats(Farm::STATS_THIS_MONTH) ?></strong>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="width: 18rem;">
            <div class="card-header">
                AI Stats
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Today <strong>200,000</strong></li>
                <li class="list-group-item">This Week <strong>200,000</strong></li>
                <li class="list-group-item">This Month <strong>200,000</strong></li>
            </ul>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="width: 18rem;">
            <div class="card-header">
                Calving Stats
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Today <strong>200,000</strong></li>
                <li class="list-group-item">This Week <strong>200,000</strong></li>
                <li class="list-group-item">This Month <strong>200,000</strong></li>
            </ul>
        </div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-md-12">
        <?= $this->render('graph/_graph', ['graphFilterOptions' => $graphFilterOptions]) ?>
    </div>
</div>