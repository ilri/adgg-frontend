<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\Farm;
use backend\modules\core\models\MilkingEvent;
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
        <ul class="list-group">
            <li class="list-group-item active">
                Farms Summary
            </li>
            <li class="list-group-item">
                Added This Week
                <strong><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_THIS_WEEK)) ?></strong>
            </li>
            <li class="list-group-item">
                Added This Month
                <strong><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_THIS_MONTH)) ?></strong>
            </li>
            <li class="list-group-item">
                Added This Year
                <strong><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_THIS_YEAR)) ?></strong>
            </li>
            <li class="list-group-item">
                All Time
                <strong><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME)) ?></strong>
            </li>
        </ul>
    </div>
    <div class="col-md-3">
        <ul class="list-group">
            <li class="list-group-item active">
                Animals Summary
            </li>
            <li class="list-group-item">
                Added This Week
                <strong><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_THIS_WEEK)) ?></strong>
            </li>
            <li class="list-group-item">
                Added This Month
                <strong><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_THIS_MONTH)) ?></strong>
            </li>
            <li class="list-group-item">
                Added This Year
                <strong><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_THIS_YEAR)) ?></strong>
            </li>
            <li class="list-group-item">
                All Time
                <strong><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_ALL_TIME)) ?></strong>
            </li>
        </ul>
    </div>
    <div class="col-md-3">
        <ul class="list-group">
            <li class="list-group-item active">
                Calving Records Summary
            </li>
            <li class="list-group-item">
                This Week
                <strong><?= Yii::$app->formatter->asDecimal(CalvingEvent::getDashboardStats(CalvingEvent::STATS_THIS_WEEK)) ?></strong>
            </li>
            <li class="list-group-item">
                This Month
                <strong><?= Yii::$app->formatter->asDecimal(CalvingEvent::getDashboardStats(CalvingEvent::STATS_THIS_MONTH)) ?></strong>
            </li>
            <li class="list-group-item">
                This Year
                <strong><?= Yii::$app->formatter->asDecimal(CalvingEvent::getDashboardStats(CalvingEvent::STATS_THIS_YEAR)) ?></strong>
            </li>
            <li class="list-group-item">
                All Time
                <strong><?= Yii::$app->formatter->asDecimal(CalvingEvent::getDashboardStats(CalvingEvent::STATS_ALL_TIME)) ?></strong>
            </li>
        </ul>
    </div>
    <div class="col-md-3">
        <ul class="list-group">
            <li class="list-group-item active">
                Milking Records Summary
            </li>
            <li class="list-group-item">
                This Week
                <strong><?= Yii::$app->formatter->asDecimal(MilkingEvent::getDashboardStats(MilkingEvent::STATS_THIS_WEEK)) ?></strong>
            </li>
            <li class="list-group-item">
                This Month
                <strong><?= Yii::$app->formatter->asDecimal(MilkingEvent::getDashboardStats(MilkingEvent::STATS_THIS_MONTH)) ?></strong>
            </li>
            <li class="list-group-item">
                This Year
                <strong><?= Yii::$app->formatter->asDecimal(MilkingEvent::getDashboardStats(MilkingEvent::STATS_THIS_YEAR)) ?></strong>
            </li>
            <li class="list-group-item">
                All Time
                <strong><?= Yii::$app->formatter->asDecimal(MilkingEvent::getDashboardStats(MilkingEvent::STATS_ALL_TIME)) ?></strong>
            </li>
        </ul>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-md-12">
        <?= $this->render('graph/_graph', ['graphFilterOptions' => $graphFilterOptions]) ?>
    </div>
</div>