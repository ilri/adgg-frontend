<?php

use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $country_id int*/
?>
<style>
    .highcharts-stack-labels text {
        transform: translate(0, 3px);
        visibility: visible;
    }
</style>
<div class="row">
    <div id="chartContainerAR" style="width:100%;"></div>
</div>
<?php
$chart_data = CountriesDashboardStats::getAnimalsByCategoriesRegionsForDataViz($filterOptions, $country_id);
$colorOptions = CountriesDashboardStats::colorOptions();
$data = [];
$colors = [
    '#7D3701', '#641E16', '#27921E',
    '#EB6060', '#489661', '#C97434',
];
shuffle($colors);
$breed_colors = [];
$empty_regions = [];
$regions = [];
//dd($chart_data, $data);
if (count($chart_data) > 0) {
    $values = [];
    foreach ($chart_data as $region => $region_data) {
        if (count($region_data)){
            $regions[] = $region;
            foreach ($region_data as $rdata){
                $values[$rdata['label']][] = $rdata['value'];
            }
        }
        else{
            $empty_regions[] = $region;
        }
    }
//    foreach ($values as $t => $dv){
//        // remove those with zeros for all regions
//        $sum = array_sum($dv);
//        if($sum > 0){
//            $data[] = [
//                'name' => $t,
//                'data' => $dv,
//                'color' => $colorOptions['animal_types'][$t],
//            ];
//        }
//    }
    foreach ($values as $t => $dv) {
        // remove those with zeros for all regions
        $sum = array_sum($dv);
        if ($sum > 0) {
            $color = isset($colorOptions['animal_types'][$t]) ? $colorOptions['animal_types'][$t] : '#000000'; // Replace #000000 with a default color
            $data[] = [
                'name'  => $t,
                'data'  => $dv,
                'color' => $color,
            ];
        }
    }

}

$series = $data;
$graphOptions = [
    'chart' => [
        'type' => 'column',
    ],
    'title' => ['text' => 'Categories of Animals registered by Region'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'categories' => array_merge($regions, $empty_regions),
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Number of Animals',
            'style' => ['fontWeight' => 'normal'],
        ]
    ],
    'plotOptions' => [
        'column' => [
            'stacking' => 'normal',
        ]
    ],
    'colors' => $colors,
];
$containerId = 'chartContainerAR';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
