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
$queryFilters = array_intersect_key($filters, array_flip(['district_id', 'ward_id', 'village_id', 'dim_range']));
?>
<div class="row">
    <div id="chartContainerAvgMilkYield" style="width:100%;"></div>
</div>
<?php
$maxMonths = DateUtils::formatDate(date('Y-m-d'), 'n');
$months = CountriesDashboardStats::getDashboardDateCategories($type = 'month', $max = $maxMonths, $format = 'Y-m-d', $from = "$year-01-01", $to = date('Y-m-d'));
$res = CountriesDashboardStats::getCountryAvgBodyWeight($filterOptions['country_id'], $region_id, $year, $queryFilters);
$data = [];

$colors = [
    '#9EEDB3', '#336083', '#004619', '#800080',
    '#C97434', '#1B4F72', '#001D00', '#487293',
    '#7197B6', '#771957', '#9BBEDA', '#4F4F4F',
    '#86AAC8', '#056030', '#7986CB', '#7F5298',
    '#2B7B48', '#E2E2E2', '#27921E', '#8B4A3E',
    '#6298D7', '#641E16', '#2EAB86', '#489661',
    '#177380', '#AE2921', '#BE78BB', '#8C2B16',
    '#853536', '#5D84A5', '#EB6060', '#E39494',
    '#C25D55', '#875F03', '#350d36',
    '#902C8E', '#878787', '#002C00',
    '#CD90C9', '#DCA8D9', '#EBC0E8',
    '#FAD8F7', '#1E1E1E', '#363636', '#A0479D',
    '#B0D2EC', '#6A6A6A', '#AF60AC', '#A4A4A4',
    '#C3C3C3', '#65B27C', '#ECBEB3', '#FFD7CD',
    '#45ADC3', '#783429', '#9F6054', '#81D097',
    '#B2776A', '#C58E82', '#D9A69A', '#C6E6FF',
];
//shuffle($colors);
$series_colors = [];
$region_data = [];

if ($region_id === null){
    foreach ($months as $month){
        $month_num = DateUtils::formatDate($month, 'n');
        $point = null;
        foreach ($res as $row){
            if ($row['month'] == $month_num){
                $point = (float) $row['avg_milk_yield_total'];
            }
        }
        $data[] = $point !== null ? (float) $point : $point;
    }
    $series = [
        [
            'name' => Country::getScalar('name', ['id' => $filterOptions['country_id']]),
            'type' => $graph_type,
            'data' => $data,
            'color' => '#C97434',
            'zIndex' => 2,
        ],
    ];
}
else {
    foreach ($res as $row){
        $region = $row['region_id'];
        $name = CountryUnits::getScalar('name', ['id' => $region]);
        $point = (float) $row['avg_milk_yield_total'];
        $year = $row['year'];
        $region_data[$name][$row['month']] = $point;
    }
    foreach ($region_data as $name => $ydata){
        $points = [];
        foreach ($months as $month) {
            $month_num = DateUtils::formatDate($month, 'n');
            $point = null;
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
            //'color' => $series_colors[$name],
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
                'data' => array_fill(0, $maxMonths, 0),
                'color' => '#F00C0C',
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
    'title' => ['text' => 'Average Milk Yield'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'categories' => array_map(function ($date){
            return DateUtils::formatDate($date, 'M Y');
        }, $months),
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Avg Milk Yield (Litres)',
        ]
    ],
    'colors' => $colors,
    'legend' => [
        'labelFormatter' => new \yii\web\JsExpression("
            function () {
                var type = this.userOptions.type;
                if(type === 'line'){
                    return this.name + ' (Avg Milk Yield)';
                }
                else {
                    return this.name + ' (Avg Milk Yield)';
                }
            }
        ")
    ]
];
$containerId = 'chartContainerAvgMilkYield';
if ($graph_type == DataViz::GRAPH_PIE){
    $this->registerJs("MyApp.modules.dashboard.piechart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");
}
else {
    $this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");
}
?>
