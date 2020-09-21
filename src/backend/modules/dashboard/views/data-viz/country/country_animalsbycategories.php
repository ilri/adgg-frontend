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
$data = [];
$colors = [
    '#fe0000', '#86cc31', '#61812e', '#94e2dd',
    '#0f767a', '#1be19f', '#0a60a8', '#d5d2e7',
    '#830c6f', '#cd49dc', '#ab6eaf', '#f6b0ec',
    '#3441c5', '#e3488e', '#562fff', '#d2c966',
    '#5e4028', '#fea53b', '#a07d62', '#20f53d',
    '#fe0000', '#b3e467', '#022114', '#cafafa',
    '#509d99', '#59faea', '#245a62', '#4cf185',
    '#2f882d', '#020b39', '#9baad8', '#2f158b',
    '#a17bf2', '#49406e', '#ef66f0', '#71114b',
    '#feafda', '#9a05cb', '#b66c96', '#88fe0e',
];
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
    foreach ($values as $t => $dv){
        if (!empty($colors)){
            $color_key = array_rand($colors);
            $color = $colors[$color_key];
            unset($colors[$color_key]);
            if (!array_key_exists($t, $breed_colors)){
                $breed_colors[$t] = $color;
            }
        }
        // remove those with zeros for all regions
        $sum = array_sum($dv);
        if($sum > 0){
            $data[] = [
                'name' => $t,
                'data' => $dv,
                'color' => $breed_colors[$t],
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
    'colors' => [
        '#800080', '#641E16', '#6298D7', '#2B7B48',
        '#9C0204', '#CD90C9', '#DCA8D9', '#EBC0E8',
        '#FAD8F7', '#000000', '#1E1E1E', '#363636',
        '#4F4F4F', '#6A6A6A', '#878787', '#A4A4A4',
        '#C3C3C3', '#E2E2E2', '#ECBEB3', '#FFD7CD',
        '#641E16', '#783429', '#8B4A3E', '#9F6054',
        '#B2776A', '#C58E82', '#D9A69A', '#C6E6FF',
    ],
];
$containerId = 'chartContainerAR';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
