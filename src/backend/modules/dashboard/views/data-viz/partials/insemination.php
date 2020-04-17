<?php

use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
?>
<div class="row">
    <div id="chartContainerAI" title="" style="width:100%;"></div>
</div>
<?php
$chart_data = CountriesDashboardStats::getTestDayMilkGroupedByRegions();
$data = [];
if (count($chart_data) > 0) {
    foreach ($chart_data as $cdata) {
        $data[] = [
            'name' => $cdata['label'],
            'y' => floatval(number_format($cdata['value'], 2, '.', '')),
        ];
    }
}
$series = [
    [
        'name' => 'Fresian',
        //'data' => $data,
        'data' => [10,12,13],
        'color' => '#771957',
    ],
    [
        'name' => 'Zebu',
        //'data' => $data,
        'data' => [15,16,17],
        'color' => '#7F5298',
    ],
    [
        'name' => 'Borana',
        //'data' => $data,
        'data' => [14,15,15],
        'color' => '#7986CB',
    ],
];
$graphOptions = [
    'chart' => [
        'type' => 'bar',
    ],
    'title' => ['text' => 'Total AI by Breeds'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'title' => [
            'text' => 'Countries',
            'style' => ['fontWeight' => 'normal'],
        ],
        'categories' => ['KE', 'ET', 'TZ']
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
];
$containerId = 'chartContainerAI';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
