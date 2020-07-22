<?php

/* @var $this yii\web\View */

use backend\modules\core\Constants;
use backend\modules\reports\Constants as ReportConstants;
use common\helpers\Lang;
use common\helpers\Url;
use yii\bootstrap\Html;

/* @var int $country_id */

$this->title = 'Standard Extracts';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->user->canView(Constants::RES_REPORT_BUILDER)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="well">
            <h3><?= Lang::t(strtoupper($this->title)) ?></h3>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2 hidden ">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['view', 'type' => ReportConstants::REPORT_TYPE_MILKDATA, 'country_id' => $country_id]) ?>" class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title"><?= Lang::t('Milk Data'); ?></div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2 hidden ">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['view', 'type' => ReportConstants::REPORT_TYPE_PEDIGREE, 'country_id' => $country_id]) ?>" class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title"><?= Lang::t('Pedigree'); ?></div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['view', 'type' => ReportConstants::REPORT_TYPE_PEDIGREE_FILE, 'country_id' => $country_id]) ?>" class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title"><?= Lang::t('Pedigree File'); ?></div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['view', 'type' => ReportConstants::REPORT_TYPE_TESTDAY_MILKDATA, 'country_id' => $country_id]) ?>" class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title"><?= Lang::t('Test Day Milk Data'); ?></div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['view', 'type' => ReportConstants::REPORT_TYPE_CALFDATA, 'country_id' => $country_id]) ?>" class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title"><?= Lang::t('Calf Data'); ?></div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['view', 'type' => ReportConstants::REPORT_TYPE_TESTDAY_MILKDATA2, 'country_id' => $country_id]) ?>" class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__title"><?= Lang::t('Test Day Milk Data 2'); ?></div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>