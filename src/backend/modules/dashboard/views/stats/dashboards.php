<?php


/* @var $this yii\web\View */

use common\helpers\Lang;
use common\helpers\Url;

$this->title = 'Reports Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="well">
            <h3><?= Lang::t('Quick Reports Dashboards') ?></h3>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/farm-summary?report=farmers']) ?>"
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
                        <a href="<?= Url::to(['/dashboard/stats/animal-summary?report=animals']) ?>"
                           class="kt-iconbox kt-iconbox--active">

                            <div class="kt-iconbox__title">
                                <?= Lang::t('Animals Registered'); ?>
                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>

                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/dash1']) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">LSF Farm Stats</div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/dash2']) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">Milk Report(Test Day)</div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/dash3']) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">Genotyped Animals By Regions</div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['/dashboard/stats/dash4']) ?>"
                           class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title">Insemination,PD And Calving</div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
            </div>
        </div>
    </div>
</div>