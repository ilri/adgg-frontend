<?php

use backend\modules\auth\Session;
use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Farm;
use common\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $filterOptions array */

$countries = CountriesDashboardStats::getDashboardCountryCategories();
$country_ids = array_keys($countries);
$projects = Choices::getList(ChoiceTypes::CHOICE_TYPE_PROJECT, false);

?>
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered table-hover dashboard-table">
            <thead>
            <tr>
                <th></th>
                <th>Total LSF</th>
                <th>Total SSF</th>
                <th>Total Cattle</th>
                <th>Undefined Farm Type</th>
                <th>Total Farms</th>
                <th>Total Cattle</th>
            </tr>
            </thead>
            <tbody>
                <?php if(Session::isPrivilegedAdmin() || Session::isCountryUser()): ?>
                    <tr>
                        <th class="dt-category-name">Country List</th>
                        <td colspan="4"></td>
                    </tr>
                    <?php foreach ($countries as $k => $name): ?>
                        <tr>
                            <th class="dt-row-name"><a href="<?= Url::to(['/dashboard/data-viz/index', 'country_id' => $k]) ?>"><?= Html::encode($name) ?></a></th>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => '1', 'country_id' => $k])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => '2', 'country_id' => $k])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => '3', 'country_id' => $k])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => null, 'country_id' => $k])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['country_id' => $k])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_ALL_TIME, false, [],  'created_at', null, null, ['country_id' => $k])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if(Session::isPrivilegedAdmin() || Session::isCountryUser()): ?>
                    <tr>
                        <th class="dt-category-name">Projects</th>
                        <td colspan="4"></td>
                    </tr>
                    <?php foreach ($projects as $k => $name): ?>
                        <tr>
                            <th class="dt-row-name"><?= Html::encode($name) ?></th>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => 'LSF', 'project' => $name, 'country_id' => $country_ids])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => 'MSF', 'project' => $name, 'country_id' => $country_ids])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => 'SSF', 'project' => $name, 'country_id' => $country_ids])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => null, 'project' => $name, 'country_id' => $country_ids])) ?></td>
                            <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['project' => $name, 'country_id' => $country_ids])) ?></td>
                            <?php
                                $count = Animal::find()->select('id')
                                    ->joinWith('farm')
                                    ->andWhere([Animal::tableName() . '.country_id' => $country_ids])
                                    ->andWhere(Farm::tableName() . '.project = :project', [':project' => $name])
                                    ->count();
                            ?>
                            <td><?= Yii::$app->formatter->asDecimal($count) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
