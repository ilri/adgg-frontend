<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\Farm;
use backend\modules\core\models\MilkingEvent;
use common\helpers\Lang;
use common\helpers\Url;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
?>

<div class="row">
    <div class="col-md-3">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <a href="<?= Url::to(['/dashboard/stats/summary?report=farmers_animals']) ?>" class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon">
                    <div class="kt-iconbox__icon-bg"></div>
                    <i class="flaticon2-chart"></i>
                </div>
                <div class="kt-iconbox__title">Farmers & Animals Registered by PRAs</div>
            </a>
        </div>
        <!--end::Portlet-->
    </div>
    <div class="col-md-3 hidden">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <a href="<?= Url::to(['/dashboard/stats/summary?report=test_day_milk_data']) ?>" class="kt-iconbox kt-iconbox--active">
                <div class="kt-iconbox__icon">
                    <div class="kt-iconbox__icon-bg"></div>
                    <i class="flaticon2-chart"></i>
                </div>
                <div class="kt-iconbox__title">ADGG Test-day Milk data</div>
            </a>
        </div>
        <!--end::Portlet-->
    </div>
</div>
<br/>