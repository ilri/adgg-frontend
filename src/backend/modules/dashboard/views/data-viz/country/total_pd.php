<?php

use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use backend\modules\dashboard\models\DataViz;
use common\helpers\DateUtils;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */

$year = Yii::$app->request->get('year', date("Y"));
$region_id = Yii::$app->request->get('region_id', null);
$graph_type = Yii::$app->request->get('graph_type', DataViz::GRAPH_LINE);
$filters = Yii::$app->request->getQueryParams();
$queryFilters = array_intersect_key($filters, array_flip(['district_id', 'ward_id', 'village_id', 'field_agent_id']));
?>
<div class="row">
    <div id="chartContainerAIPreg" style="width:100%;"></div>
</div>
<?php
//$years = CountriesDashboardStats::rangeYears();
$months = CountriesDashboardStats::getDashboardDateCategories($type = 'month', $max = 12, $format = 'Y-m-d', $from = "$year-01-01", $to = "$year-12-31");
$res = CountriesDashboardStats::getCountryTotalPD($filterOptions['country_id'], $region_id, $year, $queryFilters);

$data = [];

$colors = [
    '#9EEDB3', '#001D00', '#004619',
    '#1B4F72', '#5D84A5', '#350d36',
    '#771957', '#7F5298', '#65B27C',
    '#D3E36F', '#DBB450', '#C97434',
    '#AE2921', '#27921E', '#F00C0C',
    '#C25D55', '#FF9900', '#875F03',
    '#EBC0E8', '#000000', '#363636',
    '#C6E6FF', '#F6FF00', '#022114',
    '#509d99', '#59faea', '#245a62',
    '#61812e', '#4cf185', '#9baad8',
    '#0f767a', '#1be19f', '#0a60a8',
    '#e3488e', '#d2c966', '#2f158b',
    '#a07d62', '#20f53d', '#020b39',
    '#fe0000', '#b3e467',
];
shuffle($colors);
$series_colors = [];
$region_data = [];

if ($region_id === null){
    foreach ($months as $month){
        $month_num = DateUtils::formatDate($month, 'n');
        $point = 0;
        foreach ($res as $row){
            if ($row['month'] == $month_num){
                $point = (float) $row['pd_examinations'];
            }
        }
        $data[] = (float) $point;
    }
    $series = [
        [
            'name' => Country::getScalar('name', ['id' => $filterOptions['country_id']]),
            'type' => $graph_type,
            'data' => $data,
            'color' => '#641E16',
            'zIndex' => 2,
        ],
    ];
}
else {
    foreach ($res as $row){
        $region = $row['region_id'];
        $name = CountryUnits::getScalar('name', ['id' => $region]);
        $point = (float) $row['pd_examinations'];
        $year = $row['year'];
        $region_data[$name][$row['month']] = $point;
    }
    foreach ($region_data as $name => $ydata){
        $points = [];
        foreach ($months as $month) {
            $month_num = DateUtils::formatDate($month, 'n');
            $point = 0;
            foreach ($ydata as $y => $value){
                if ($y == $month_num){
                    $point = (float) $value;
                }
            }
            $points[] = $point;
        }
        if (!empty($colors)){
            $color_key = array_rand($colors);
            $color = $colors[$color_key];
            unset($colors[$color_key]);
            if (!array_key_exists($name, $series_colors)){
                $series_colors[$name] = $color;
            }
        }

        $item = [
            'name' => $name,
            'type' => $graph_type,
            'data' => $points,
            'color' => $series_colors[$name],
            'zIndex' => 2,
        ];
        $data[] = $item;
    }
    if(count($data)){
        $series = $data;
    }
    else {
        $series = [
            [
                'name' => Country::getScalar('name', ['id' => $filterOptions['country_id']]),
                'data' => [0,0,0,0,0,0,0,0,0,0,0,0],
                'color' => '#641E16',
            ]
        ];
    }

}
//dd($months, $res, $region_data, $data);

$_series = [
    [
        'name' => Country::getScalar('name', ['id' => $filterOptions['country_id']]),
        'type' => $graph_type,
        'data' => $data,
        'color' => '#771957',
        'zIndex' => 2,
    ],

];
$graphOptions = [
    'title' => ['text' => 'Total Pregnancy Diagnosis done'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'categories' => array_map(function ($date){
            return DateUtils::formatDate($date, 'M Y');
        }, $months),
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Total Pregnancy Diagnosis',
        ]
    ],
    'colors' => $colors,
    'legend' => [
        'labelFormatter' => new \yii\web\JsExpression("
            function () {
                var type = this.userOptions.type;
                if(type === 'line'){
                    return this.name;
                }
                else {
                    return this.name;
                }
            }
        ")
    ]
];
$containerId = 'chartContainerAIPreg';
if ($graph_type == DataViz::GRAPH_PIE){
    $this->registerJs("MyApp.modules.dashboard.piechart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");
}
else {
    $this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");
}
?>
