<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Farm;
use common\helpers\DateUtils;
use common\helpers\DbUtils;
use common\helpers\Lang;

/* @var $this yii\web\View */
/* @var $filterOptions array */
?>

<div class="col-md-4">
    <div class='card my-2 '>
        <div class="card-body">
            <h1 class="text-center kt-label-font-color-4 mb-0">
                <?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME)) ?>
            </h1>
            <h5 class="text-center mb-4">Farms Registered</h5>
            <p class="text-center">
                <span class="text-center kt-font-success">+<strong><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_THIS_MONTH)) ?></strong> this month</span>
            </p>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class='card my-2 '>
        <div class="card-body">
            <h1 class="text-center kt-label-font-color-4 mb-0">
                <?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_ALL_TIME)) ?>
            </h1>
            <h5 class="text-center mb-4">Animals Registered</h5>
            <p class="text-center">
                <span class="kt-font-success">+<strong><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_THIS_MONTH)) ?></strong> this month</span>
            </p>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class='card my-2 '>
        <div class="card-body">
            <h1 class="text-center kt-label-font-color-4 mb-0">
                <?= Yii::$app->formatter->asDecimal(CountriesDashboardStats::getAnimalsWithMilk()) ?>
            </h1>
            <h5 class="text-center mb-4">Animals with Milk Records</h5>
            <p class="text-center">
                <span class="kt-font-success">+<strong><?= Yii::$app->formatter->asDecimal(CountriesDashboardStats::getAnimalsWithMilk('', [], Animal::STATS_THIS_MONTH)) ?></strong> this month</span>
            </p>
        </div>
    </div>
</div>
