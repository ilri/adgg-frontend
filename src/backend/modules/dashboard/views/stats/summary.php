<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\Farm;
use backend\modules\core\models\MilkingEvent;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\highchart\HighChart;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>

<div class="row">
    <div class="col-md-12">
        <?= $this->render('graph/_graphFilters', ['filterOptions' => $graphFilterOptions, 'graphType' => $graphType]) ?>
    </div>
    <div class="col-md-6">
        <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="col-md-12 kt-iconbox kt-iconbox--active">
                <?= $this->render('graph/_widget', ['graphType' => HighChart::GRAPH_PIE, 'graphFilterOptions' => $graphFilterOptions]) ?>
            </div>
        </div>
        <!--end::Portlet-->
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Farm::getCount(['org_id' => $graphFilterOptions['org_id']])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">ADGG Number of Farmers</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Animal::getCount(['org_id' => $graphFilterOptions['org_id']])) ?></span>
                        </div>
                        <div class="kt-iconbox__title">ADGG Number of Animals</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 1])->count()) ?></span>
                        </div>
                        <div class="kt-iconbox__title">ADGG Male Household headed Farmers</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 2])->count()) ?></span>
                        </div>
                        <div class="kt-iconbox__title">ADGG Female Household headed Farmers</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="kt-portlet">
                    <div class="kt-iconbox kt-iconbox--active">
                        <div class="kt-iconbox__icon mb-0">
                            <div class="kt-iconbox__icon-bg"></div>
                            <span><?= number_format(Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => [1,2]])->count()) ?></span>
                        </div>
                        <div class="kt-iconbox__title">Households headed by both male and female members</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br/>