<?php

use backend\modules\auth\Session;
use backend\modules\core\models\Animal;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Farm;
use common\helpers\Lang;
use common\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
/* @var $countries \backend\modules\core\models\Country[] */
/* @var $farm Farm */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
?>
<div class="row">
    <?php foreach ($countries as $country): ?>
        <?php if (Session::getCountryId() == $country->id || Session::isPrivilegedAdmin()): ?>
            <div class="col-md-6">
                <div class='card my-2 '>
                    <h3 class="card-header bg-grey border-4">
                        <?php if (Session::isVillageUser()): ?>
                            <?= $unitName = CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                        <?php elseif (Session::isWardUser()): ?>
                            <?= $unitName = CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                        <?php elseif (Session::isDistrictUser()): ?>
                            <?= $unitName = CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                        <?php elseif (Session::isRegionUser()): ?>
                            <?= $unitName = CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                        <?php else: ?>
                            <?= Html::encode($country->name) ?>
                        <?php endif; ?>
                    </h3>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h5 class="text-center font-weight-bold"><?= Lang::t('Number Of Farms') ?></h5>
                                <h1 class="text-center kt-font-info">
                                    <?= CountriesDashboardStats::getFarmCounts($country->id, false) ?>
                                </h1>
                                <h6 class="text-center font-weight-bold"><?= Lang::t('Number Of Animals') ?></h6>
                                <h1 class="text-center kt-font-info">
                                    <?= CountriesDashboardStats::getAnimalCounts($country->id) ?>
                                </h1>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card-footer bg-white d-flex justify-content-center justify-content-md-end border-0">
                                    <a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space"
                                       href="<?= Url::to(['/dashboard/stats/dash', 'country_id' => $country->id]) ?>">
                                        <?= Lang::t('View Dashboard') ?>
                                        <i class="far fa-chevron-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
