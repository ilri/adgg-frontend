<?php

use backend\modules\auth\Session;
use backend\modules\core\models\Client;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Organization;
use common\helpers\Lang;
use common\helpers\Url;
use yii\helpers\Html;

/* @var $country \backend\modules\core\models\Country */
$this->title = 'Reports Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12" title="Click to view details">
        <div class="well">
            <h3>
                <?php if (Session::isVillageUser()): ?>
                    <?php $unitName = Lang::t('Quick Reports Dashboards for') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                <?php elseif (Session::isWardUser()): ?>
                    <?= $unitName = Lang::t('Quick Reports Dashboards for') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                <?php elseif (Session::isDistrictUser()): ?>
                    <?= $unitName = Lang::t('Quick Reports Dashboards for') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                <?php elseif (Session::isRegionUser()): ?>
                    <?= $unitName = Lang::t('Quick Reports Dashboards for') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                <?php elseif (Session::isOrganizationUser()): ?>
                    <?= $unitName = Lang::t('Quick Reports Dashboards for') . ' ' . Organization::getScalar('name', ['id' => Session::getOrgId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                <?php elseif (Session::isOrganizationClientUser()): ?>
                    <?= $unitName = Lang::t('Quick Reports Dashboards for') . ' ' . Client::getScalar('name', ['id' => Session::getClientId(), 'country_id' => $country->id]) . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                <?php else: ?>
                    <?= Lang::t('Quick Reports Dashboards for {country}', ['country' => $country->name]) ?>
                <?php endif; ?>
            </h3>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/farm-summary', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">

                            <div class="kt-iconbox__title">
                                <?= Lang::t('Farms Registered'); ?>
                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/animal-summary', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">

                            <div class="kt-iconbox__title">
                                <?= Lang::t('Animals Registered '); ?>
                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>

                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet" title="Click to view details">
                        <a href="<?= Url::to(['/dashboard/stats/lsf', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">
                                <?= Lang::t('LSF Farm Stats'); ?>                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/test-day', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">
                                <?= Lang::t('Milk Report(Test Day) '); ?>                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/genotype', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">
                                <?= Lang::t('Genotyped Animals'); ?>                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/insemination-pd-calving', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">
                                <?= Lang::t('Insemination,PD And Calving'); ?>
                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
            </div>
        </div>
    </div>
</div>