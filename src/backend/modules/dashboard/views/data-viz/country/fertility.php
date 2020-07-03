<?php

use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */

$region_id = Yii::$app->request->get('region_id', null);
?>
<div class="row">
    <div id="chartContainerFertility" style="width:100%;"></div>
</div>
<?php
$years = CountriesDashboardStats::rangeYears();
$res = CountriesDashboardStats::getCountryFertility($filterOptions['country_id'], $region_id);
$data = [];

foreach ($years as $year){
    $point = 0;
    foreach ($res as $row){
        if ($row['year'] == $year){
            $point = (float) $row['avg_fertility'];
        }
    }
    $data[] = (float) $point;
}
//dd($years, $res, $data);
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
    'title' => ['text' => 'Fertility'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'categories' => $years,
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Fertilities',
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
                    return this.name + ' (Fertilities)';
                }
                else {
                    return this.name + ' (Fertilities)';
                }
            }
        ")
    ]
];
$containerId = 'chartContainerFertility';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
