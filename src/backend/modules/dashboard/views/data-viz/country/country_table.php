<?php

use backend\modules\auth\Session;
use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Farm;
use common\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $filterOptions array */

$countries = CountriesDashboardStats::getDashboardCountryCategories();
$regions = CountryUnits::getListData('id', 'name', false, ['country_id' => $country_id,'level' => CountryUnits::LEVEL_REGION]);

$region_ids = array_keys($regions);

?>
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered table-hover dashboard-table">
            <thead>
            <tr>
                <th></th>
                <th>Total LSF</th>
                <th>Total SSF</th>
                <th>Total MSF</th>
                <th>Undefined Farm Types</th>
                <th>Total Farms</th>
                <th>Total Cattle</th>
            </tr>
            </thead>
            <tbody>
                <?php if(Session::isPrivilegedAdmin() || Session::isCountryUser()): ?>
                    <tr>
                        <th class="dt-category-name">Region List</th>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <th class="dt-row-name"><?= Html::encode("All Regions") ?></th>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => '2', 'country_id' => $country_id])) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => '1', 'country_id' => $country_id])) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => '3', 'country_id' => $country_id])) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => null, 'country_id' => $country_id])) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['country_id' => $country_id])) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_ALL_TIME, false, [],  'created_at', null, null, ['country_id' => $country_id])) ?></td>
                    </tr>
                    <?php foreach ($regions as $k => $name): ?>
                        <tr>
                            <th class="dt-row-name"><?= Html::encode($name) ?></th>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => '2', 'region_id' => $k])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => '1', 'region_id' => $k])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => '3', 'region_id' => $k])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => null, 'region_id' => $k])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['region_id' => $k])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_ALL_TIME, false, [],  'created_at', null, null, ['region_id' => $k])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
