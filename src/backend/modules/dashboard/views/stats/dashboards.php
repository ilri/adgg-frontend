<?php

use common\helpers\Lang;
use common\helpers\Url;

/* @var $country \backend\modules\core\models\OrganizationRef */
$this->title = 'Reports Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12" title="Click to view details">
        <div class="well">
            <h3><?= Lang::t('Quick Reports Dashboards for {country}', ['country' => $country->name]) ?></h3>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/farm-summary', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">

                            <div class="kt-iconbox__title">
                                <?= Lang::t('Farms Registered in {country}', ['country' => $country->name]); ?>
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
                                <?= Lang::t('Animals Registered in {country}', ['country' => $country->name]); ?>
                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>

                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet" title="Click to view details">
                        <a href="<?= Url::to(['/dashboard/stats/dash1', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">
                                <?= Lang::t('LSF Farm Stats in {country}', ['country' => $country->name]); ?>                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/dash2', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">
                                <?= Lang::t('Milk Report(Test Day) in {country}', ['country' => $country->name]); ?>                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/dash3', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">
                                <?= Lang::t('Genotyped Animals in {country}', ['country' => $country->name]); ?>                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/dash4', 'country_id' => $country->id]) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">
                                <?= Lang::t('Insemination,PD And Calving in {country}', ['country' => $country->name]); ?>
                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
            </div>
        </div>
    </div>
</div>