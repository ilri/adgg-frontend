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

?>
<div class="row">
    <div id="chartContainerAIBreeds" title="" style="width:100%;"></div>
</div>
<?php
$months = CountriesDashboardStats::getDashboardDateCategories($type = 'month', $max = 12, $format = 'Y-m-d', $from = "$year-01-01", $to = "$year-12-31");
$res = CountriesDashboardStats::getCountryMonthlyInseminations($filterOptions['country_id'], $region_id, $year);
$colors = [
    '#800080', '#641E16', '#6298D7', '#2B7B48', '#9C0204',
    '#CD90C9', '#DCA8D9', '#EBC0E8',
    '#FAD8F7', '#000000', '#1E1E1E', '#363636',
    '#4F4F4F', '#6A6A6A', '#878787', '#A4A4A4',
    '#C3C3C3', '#E2E2E2', '#ECBEB3', '#FFD7CD',
    '#641E16', '#783429', '#8B4A3E', '#9F6054',
    '#B2776A', '#C58E82', '#D9A69A', '#C6E6FF',
];
$breed_colors = [];
$data = [];
$breed_data = [];
foreach ($res as $row){
    $point = (int) $row['inseminations'];
    $breed_data[$row['main_breed']]['name'] = $row['label'];
    $breed_data[$row['main_breed']]['data'][$row['month']] = $point;
}
foreach ($breed_data as $breed => $bd){
    $points = [];
    foreach ($months as $month){
        $point = 0;
        $month_num = DateUtils::formatDate($month, 'n');
        if (array_key_exists($month_num, $bd['data'])){
            $point = $bd['data'][$month_num];
        }
        $points[] = $point;
    }
    # set a random color
    $color_key = array_rand($colors);
    $color = $colors[$color_key];
    unset($colors[$color_key]);
    if (!array_key_exists($breed, $breed_colors)){
        $breed_colors[$breed] = $color;
    }
    $data[$breed]['name'] = $bd['name'];
    $data[$breed]['data'] = $points;
    $data[$breed]['color'] = $breed_colors[$breed];
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
            'data' => [0,0,0,0,0,0,0,0,0,0,0,0],
            'color' => '#771957',
        ]
    ];
}

$graphOptions = [
    'chart' => [
        'type' => $graph_type,
    ],
    'title' => ['text' => 'Monthly Inseminations per Breed'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'title' => [
            'text' => 'Monthly Inseminations',
            'style' => ['fontWeight' => 'normal'],
        ],
        'categories' => array_map(function ($date){
            return \common\helpers\DateUtils::formatDate($date, 'M Y');
        }, $months),
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Monthly Inseminations',
            'style' => ['fontWeight' => 'normal'],
        ]
    ],
    'plotOptions' => [
        'series' => [
            'stacking' => 'normal',
        ]
    ],
    'colors' => [
        '#1B4F72', '#336083', '#487293', '#5D84A5',
        '#7197B6', '#86AAC8', '#9BBEDA', '#B0D2EC',
        '#177380', '#D3E36F', '#DBB450', '#C97434',
        '#AE2921', '#8C2B16', '#F00C0C', '#350d36',
        '#EB6060', '#E39494', '#9C0204', '#853536',
        '#C25D55', '#FF9900', '#875F03', '#F6FF00',
        '#800080', '#902C8E', '#A0479D', '#AF60AC',
        '#BE78BB', '#CD90C9', '#DCA8D9', '#EBC0E8',
    ],
];
$containerId = 'chartContainerAIBreeds';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
