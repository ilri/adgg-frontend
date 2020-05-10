<?php

use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
?>
<div class="row">
    <div id="chartContainer" title="" style="width:100%;"></div>
</div>
<?php
$chart_data = CountriesDashboardStats::getMilkProductionForDataViz($filterOptions);
//dd($filterOptions);
$data = [];
if (count($chart_data) > 0) {
    foreach ($chart_data as $country => $country_data) {
        $values = [];
        foreach ($country_data as $cdata){
            $values[] = $cdata['value'];
        }
        $data[] = [
            'name' => $country,
            'data' => array_sum($values) < 1 ? $values : array_map(function ($value){
                return $value > 0 ? $value : null;
            }, $values),
        ];
    }
}
/*
$series = [
    [
        'name' => 'Kenya',
        //'data' => $data,
        'data' => [1,2,3,4,5,5,6,7,8,2,9,8],
        'color' => '#771957',
    ],
    [
        'name' => 'Ethiopia',
        //'data' => $data,
        'data' => [5,6,7,8,2,1,2,3,4,5,7,9],
        'color' => '#7986CB',
    ],
];
*/
$series = $data;
$graphOptions = [
    'title' => ['text' => 'Milk Production'],
    'subtitle' => ['text' => '12 Month trend'],
    'xAxis' => [
        'title' => [
            'text' => '',
            'style' => ['fontWeight' => 'normal'],
        ],
        'categories' =>
            array_map(function ($date){
                return \common\helpers\DateUtils::formatDate($date, 'M Y');
            }, CountriesDashboardStats::getDashboardDateCategories()),
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Milk Production (Litres)',
            'style' => ['fontWeight' => 'normal'],
        ]
    ],
    'colors' => [
        '#771957',
        '#7986CB',
        '#7F5298',
    ],
];
$containerId = 'chartContainer';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
