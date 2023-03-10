<?php

use backend\modules\auth\Session;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\dashboard\models\DataViz;
use common\helpers\DateUtils;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */

$year = Yii::$app->request->get('year', date("Y"));
$region_id = Yii::$app->request->get('region_id', null);
$graph_type = Yii::$app->request->get('graph_type', DataViz::GRAPH_COLUMN);
$filters = Yii::$app->request->getQueryParams();
$queryFilters = array_intersect_key($filters, array_flip(['district_id', 'ward_id', 'village_id', 'field_agent_id']));

?>
<div class="row">
    <div id="chartContainerAIBreeds" title="" style="width:100%;"></div>
</div>
<?php
$maxMonths = DateUtils::formatDate(date('Y-m-d'), 'n');
$months = CountriesDashboardStats::getDashboardDateCategories($type = 'month', $max = $maxMonths, $format = 'Y-m-d', $from = "$year-01-01", $to = date('Y-m-d'));
$res = CountriesDashboardStats::getCountryMonthlyInseminations($filterOptions['country_id'], $region_id, $year, $queryFilters);
$colorOptions = CountriesDashboardStats::breedColorsFromGroups();
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
//shuffle($colors);
$breed_colors = [];
$data = [];
$breed_data = [];
foreach ($res as $row){
    $point = (int) $row['inseminations'];
    $breed_data[$row['ai_sire_breed']]['name'] = $row['ai_sire_breed_label'];
    $breed_data[$row['ai_sire_breed']]['data'][$row['month']] = $point;
}
foreach ($breed_data as $breed => $bd){
    $points = [];
    foreach ($months as $month){
        $point = null;
        $month_num = DateUtils::formatDate($month, 'n');
        if (array_key_exists($month_num, $bd['data'])){
            $point = $bd['data'][$month_num];
        }
        $points[] = $point;
    }
    $data[$breed]['name'] = $bd['name'];
    $data[$breed]['data'] = $points;
    $data[$breed]['color'] = $colorOptions[$bd['name']];
}
//dd($res, $breed_data, array_values($data));

$_series = [
    [
        'name' => 'Fresian',
        'data' => [0,0,0,0,0,0,0,0,0,0,0,0],
        'color' => '#771957',
    ],
    [
        'name' => 'Zebu',
        'data' => [0,0,0,0,0,0,0,0,0,0,0,0],
        'color' => '#7F5298',
    ],
    [
        'name' => 'Borana',
        'data' => [0,0,0,0,0,0,0,0,0,0,0,0],
        'color' => '#7986CB',
    ],
];
if(count($data)){
    $series = array_values($data);
}
else {
    $series = [
        [
            'name' => Country::getScalar('name', ['id' => $filterOptions['country_id']]),
            'data' => array_fill(0, $maxMonths, 0),
            'color' => '#7986CB',
        ]
    ];
}

$graphOptions = [
    'chart' => [
        'type' => $graph_type,
    ],
    'title' => ['text' => 'Total Inseminations Done By Breed'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'title' => [
            'text' => 'Total Inseminations',
            'style' => ['fontWeight' => 'normal'],
        ],
        'categories' => array_map(function ($date){
            return \common\helpers\DateUtils::formatDate($date, 'M Y');
        }, $months),
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Total Inseminations',
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
$containerId = 'chartContainerAIBreeds';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
