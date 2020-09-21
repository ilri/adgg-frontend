<?php

use backend\modules\auth\Session;
use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $country_id int*/
?>
<div class="row">
    <div id="chartContainerBreeds" title="" style="width:100%;"></div>
</div>
<?php
$chart_data = CountriesDashboardStats::getAnimalBreedsByRegionsForDataViz($country_id);
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
$empty_regions = [];
$breed_colors = [];
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
        'type' => 'bar',
    ],
    'title' => ['text' => 'Types of Breeds kept per Region'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'categories' => array_merge($regions, $empty_regions),
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Totals',
            'style' => ['fontWeight' => 'normal'],
        ]
    ],
    'plotOptions' => [
        'series' => [
            'stacking' => 'normal',
        ]
    ],
    'colors' => [
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
    ],
];
$containerId = 'chartContainerBreeds';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
