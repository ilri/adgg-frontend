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
    '#9EEDB3', '#1B4F72', '#001D00',
    '#5D84A5', '#771957', '#7F5298',
    '#350d36', '#65B27C', '#004619',
    '#D3E36F', '#C97434', '#AE2921',
    '#DBB450', '#27921E', '#0a60a8',
    '#C25D55', '#875F03',
    '#EBC0E8', '#1be19f',
    '#C6E6FF', '#022114', '#245a62',
    '#509d99', '#59faea',
    '#61812e', '#9baad8',
    '#e3488e', '#d2c966', '#2f158b',
    '#a07d62', '#20f53d', '#020b39',
    '#b3e467', '#4cf185', '#0f767a',
];
shuffle($colors);
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
                $breed_colors[$rdata['label']] = $rdata['color'];
                $values[$rdata['label']][] = $rdata['value'];
            }
        }
        else{
            $empty_regions[] = $region;
        }
    }
    foreach ($values as $t => $dv){
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
    'colors' => $colors,
];
$containerId = 'chartContainerBreeds';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
