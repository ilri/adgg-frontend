<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Farm;
use common\helpers\Lang;

/* @var $this yii\web\View */
/* @var $filterOptions array */
?>

<div class="col-md-3">
    <div class='card my-2 '>
        <h3 class="card-header bg-white border-4">Total LSF Registered</h3>
        <div class="card-body">
            <h1 class="text-left kt-label-font-color-4">
                <?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => 'LSF'])) ?>
            </h1>
            <span class="kt-font-success">+<strong><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_THIS_MONTH,  false, [],  'created_at', null, null, ['farm_type' => 'LSF'])) ?></strong> this month</span>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class='card my-2 '>
        <h3 class="card-header bg-white border-4">Total SSF Registered</h3>
        <div class="card-body">
            <h1 class="text-left kt-label-font-color-4">
                <?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME,  false, [],  'created_at', null, null, ['farm_type' => 'SSF'])) ?>
            </h1>
            <span class="kt-font-success">+<strong><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_THIS_MONTH,  false, [],  'created_at', null, null, ['farm_type' => 'SSF'])) ?></strong> this month</span>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class='card my-2 '>
        <h3 class="card-header bg-white border-4">Total Farms Registered</h3>
        <div class="card-body">
            <h1 class="text-left kt-label-font-color-4">
                <?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME)) ?>
            </h1>
            <span class="kt-font-success">+<strong><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_THIS_MONTH)) ?></strong> this month</span>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class='card my-2 '>
        <h3 class="card-header bg-white border-4">Total Cattle Registered</h3>
        <div class="card-body">
            <h1 class="text-left kt-label-font-color-4">
                <?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_ALL_TIME)) ?>
            </h1>
            <span class="kt-font-success">+<strong><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_THIS_MONTH)) ?></strong> this month</span>
        </div>
    </div>
</div>
