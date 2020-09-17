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
    '#800080', '#641E16', '#6298D7', '#2B7B48', '#9C0204',
    '#CD90C9', '#DCA8D9', '#EBC0E8',
    '#FAD8F7', '#000000', '#1E1E1E', '#363636',
    '#4F4F4F', '#6A6A6A', '#878787', '#A4A4A4',
    '#C3C3C3', '#E2E2E2', '#ECBEB3', '#FFD7CD',
    '#641E16', '#783429', '#8B4A3E', '#9F6054',
    '#B2776A', '#C58E82', '#D9A69A', '#C6E6FF',
];
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
            'color' => '#771957',
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
                'color' => '#771957',
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
    'colors' => [
        '#AE2921', '#000000', '#004619',
        '#7F5298', '#7986CB', '#81D097',
    ],
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
