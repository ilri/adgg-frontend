<?php

use backend\modules\auth\Session;
use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */

$year = Yii::$app->request->get('year', date("Y"));
$region_id = Yii::$app->request->get('region_id', null);

?>
<div class="row">
    <div id="chartContainerAIBreeds" title="" style="width:100%;"></div>
</div>
<?php


$series = [
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

//$series = $data;
$graphOptions = [
    'chart' => [
        'type' => 'column',
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
        }, CountriesDashboardStats::getDashboardDateCategories($type = 'month', $max = 12, $format = 'Y-m-d', $from = '2020-01-31', $to = '2020-12-31')),
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
