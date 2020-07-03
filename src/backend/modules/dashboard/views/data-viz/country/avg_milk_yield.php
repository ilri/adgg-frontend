<?php

use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */

$year = Yii::$app->request->get('year', date("Y"));
$region_id = Yii::$app->request->get('region_id', null);
?>
<div class="row">
    <div id="chartContainerAvgMilkYield" style="width:100%;"></div>
</div>
<?php
$quarters = CountriesDashboardStats::getQuarters("$year-12-31", "$year-01-01");
$res = CountriesDashboardStats::getCountryAvgBodyWeight($filterOptions['country_id'], $region_id, $year);
$data = [];

foreach ($quarters as $quarter){
    $point = 0;
    foreach ($res as $row){
        if ($row['quarter'] == $quarter->num){
            $point = (float) $row['avg_milk_yield_total'];
        }
    }
    $data[] = (float) $point;
}
//dd($res, $data);

$series = [
    [
        'name' => Country::getScalar('name', ['id' => $filterOptions['country_id']]),
        'type' => 'line',
        'data' => $data,
        'color' => '#771957',
        'zIndex' => 2,
    ],
];

$graphOptions = [
    'title' => ['text' => 'Average Milk Yield'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'categories' =>
            array_map(function (stdClass $quarter){return $quarter->period;}, $quarters),
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Avg Milk Yield',
        ]
    ],
    'colors' => [
        '#AE2921', '#000000', '#004619',
        '#7F5298', '#7986CB', '#81D097',
    ],
    'legend' => [
        'labelFormatter' => new \yii\web\JsExpression("
            function () {
                var type = this.userOptions.type;
                if(type === 'line'){
                    return this.name + ' (Avg Milk Yield)';
                }
                else {
                    return this.name + ' (Avg Milk Yield)';
                }
            }
        ")
    ]
];
$containerId = 'chartContainerAvgMilkYield';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
