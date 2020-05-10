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
/* @var $filterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];

?>

<div class="row" id="summaryChart" style="display:flex;flex-direction:row;"></div>
<br/>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card card-body" id="animalsWithMilkChart"></div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card card-body" id="breedsByCountryChart"></div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card card-body" id="animalsByCategoryChart"></div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card card-body" id="tableChart"></div>
    </div>
</div>
<?php
$options = [
    'ajaxAction' => Url::to(array_merge(['data-viz/load-chart'], $filterOptions)),
    'ajaxCharts' => [
        [
            'name' => 'summary',
            'renderContainer' => '#summaryChart'
        ],
        [
            'name' => 'animalswithmilk',
            'renderContainer' => '#animalsWithMilkChart'
        ],
        [
            'name' => 'breeds',
            'renderContainer' => '#breedsByCountryChart'
        ],
        [
            'name' => 'animalsbycategories',
            'renderContainer' => '#animalsByCategoryChart'
        ],
        [
            'name' => 'table',
            'renderContainer' => '#tableChart'
        ],

    ]
];
$this->registerJs("MyApp.modules.dashboard.dataviz(" . Json::encode($options) . ");");
?>
