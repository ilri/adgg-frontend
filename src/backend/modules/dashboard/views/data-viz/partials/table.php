<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Country;
use backend\modules\core\models\Farm;
use backend\modules\core\models\Organization;
use common\helpers\Lang;
use common\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $filterOptions array */

$countries = Country::getListData('id', 'name', false);
$projects = Choices::getList(ChoiceTypes::CHOICE_TYPE_PROJECT, false);
$orgs = Organization::getListData('id', 'name', false);
?>
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered table-hover dashboard-table">
            <thead>
            <tr>
                <th></th>
                <th>Total Farms</th>
                <th>Total LSF</th>
                <th>Total SSF</th>
                <th>Total Cattle</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="dt-category-name">Countries</th>
                    <td colspan="4"></td>
                </tr>
                <?php foreach ($countries as $k => $name): ?>
                <tr>
                    <th class="dt-row-name"><a href="<?= Url::to(['/dashboard/stats/dash', 'country_id' => $k]) ?>"><?= Html::encode($name) ?></a></th>
                    <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['country_id' => $k])) ?></td>
                    <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => 'LSF', 'country_id' => $k])) ?></td>
                    <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => 'SSF', 'country_id' => $k])) ?></td>
                    <td><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_ALL_TIME, false, [],  'created_at', null, null, ['country_id' => $k])) ?></td>
                </tr>
                <?php endforeach; ?>

                <tr>
                    <th class="dt-category-name">Organizations</th>
                    <td colspan="4"></td>
                </tr>

                <?php foreach ($orgs as $k => $name): ?>
                    <tr>
                        <th class="dt-row-name"><?= Html::encode($name) ?></th>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['org_id' => $k])) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => 'LSF', 'org_id' => $k])) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => 'SSF', 'org_id' => $k])) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal(Animal::getDashboardStats(Animal::STATS_ALL_TIME, false, [],  'created_at', null, null, ['org_id' => $k])) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th class="dt-category-name">Projects</th>
                    <td colspan="4"></td>
                </tr>
                <?php foreach ($projects as $k => $name): ?>
                    <tr>
                        <th class="dt-row-name"><?= Html::encode($name) ?></th>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['project' => $name])) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => 'LSF', 'project' => $name])) ?></td>
                        <td><?= Yii::$app->formatter->asDecimal(Farm::getDashboardStats(Farm::STATS_ALL_TIME, false, [],  'created_at', null, null, ['farm_type' => 'SSF', 'project' => $name])) ?></td>
                        <?php
                            $count = Animal::find()->select('id')->joinWith('farm')->andWhere(Farm::tableName(). '.project = :project', [':project' => $name])->count();
                        ?>
                        <td><?= Yii::$app->formatter->asDecimal($count) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
