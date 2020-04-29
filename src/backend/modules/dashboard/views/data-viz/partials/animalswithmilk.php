<?php

use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
?>
<div class="row">
    <div id="chartContainerAM" style="width:100%;"></div>
</div>
<?php
$animals = CountriesDashboardStats::getAnimalsCumulativeForDataViz($filterOptions);
# this is what takes a lot of time ~17s
$animals_with_milk = CountriesDashboardStats::getAnimalsWithMilkForDataViz($filterOptions);
//dd($animals, $animals_with_milk);

$data = [];

if (count($animals) > 0) {
    foreach ($animals as $country => $country_data) {
        $values = [];
        foreach ($country_data as $cdata){
            $values[] = $cdata['value'];
        }
        $data[] = [
            'name' => $country,
            'type' => 'column',
            'data' => $values,
        ];
    }
}
if (count($animals_with_milk) > 0) {
    foreach ($animals_with_milk as $country => $country_data) {
        $values = [];
        foreach ($country_data as $cdata){
            $values[] = $cdata['value'];
        }
        $data[] = [
            'name' => $country,
            'type' => 'line',
            'data' => $values,
            'zIndex' => 2,
        ];
    }
}
//dd($data);
/*
$_series = [
    [
        'name' => 'Kenya',
        'type' => 'line',
        'data' => [1,2,3,4,5,6,7,8,9,10,11,12],
        'color' => '#771957',
        'zIndex' => 2,
    ],
    [
        'name' => 'Ethiopia',
        'type' => 'line',
        'data' => [5,6,7,8,9,10,12,13,14,15,17,19],
        'color' => '#7986CB',
        'zIndex' => 2,
    ],
    [
        'name' => 'Tanzania',
        'type' => 'line',
        'data' => [17,18,18,18,19,21,22,23,24,25,27,29],
        'color' => '#7F5298',
        'zIndex' => 2,
    ],
    [
        'name' => 'Kenya',
        'type' => 'column',
        'data' => [10,12,13,14,15,16,17,18,19,20,21,22],
        'color' => '#056030',
    ],
    [
        'name' => 'Ethiopia',
        'type' => 'column',
        'data' => [15,16,17,18,19,20,22,23,25,27,28,30],
        'color' => '#2B7B48',
    ],
    [
        'name' => 'Tanzania',
        'type' => 'column',
        'data' => [14,15,17,18,20,23,24,26,28,29,32,25],
        'color' => '#489661',
    ],
];
*/
$series = $data;
$graphOptions = [
    'title' => ['text' => 'Animals Registered and Monitored for Milk Production'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'categories' =>
            array_map(function (stdClass $quarter){return $quarter->period;}, CountriesDashboardStats::getQuarters()),
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Number of animals',
        ]
    ],
    'colors' => [
        '#AE2921', '#000000', '#004619',
        '#7F5298', '#7986CB', '#81D097',
    ],
];
$containerId = 'chartContainerAM';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>