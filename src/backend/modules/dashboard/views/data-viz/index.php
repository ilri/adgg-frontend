<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\Farm;
use backend\modules\core\models\MilkingEvent;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\helpers\Url;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];

?>

<div class="row" id="summaryChart" style="display:flex;flex-direction:row;"></div>
<br/>
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card card-body" id="milkChart"></div>
    </div>
    <div class="col-md-6">
        <div class="card card-body" id="calfChart"></div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card card-body" id="inseminationChart"></div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card card-body" id="animalsChart"></div>
    </div>
    <div class="col-md-6">
        <div class="card card-body" id="tableChart"></div>
    </div>
</div>
<?php
$options = [
    'ajaxAction' => Url::to(['data-viz/load-chart']),
    'ajaxCharts' => [
        [
            'name' => 'summary',
            'renderContainer' => '#summaryChart'
        ],
        [
            'name' => 'milk',
            'renderContainer' => '#milkChart'
        ],
        [
            'name' => 'calf',
            'renderContainer' => '#calfChart'
        ],
        [
            'name' => 'insemination',
            'renderContainer' => '#inseminationChart'
        ],
        [
            'name' => 'animals',
            'renderContainer' => '#animalsChart'
        ],
        [
            'name' => 'table',
            'renderContainer' => '#tableChart'
        ],

    ]
];
$this->registerJs("MyApp.modules.dashboard.dataviz(" . Json::encode($options) . ");");
?>
