<?php

use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
?>
<div class="row">
    <div id="chartContainerCalf" title="" style="width:100%;"></div>
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
        'name' => 'Kenya',
        //'data' => $data,
        'data' => [10,12,13,14,15,15,16,13,12,12,10,14],
        'color' => '#771957',
    ],
    [
        'name' => 'Ethiopia',
        //'data' => $data,
        'data' => [15,16,17,18,12,11,12,13,14,15,17,19],
        'color' => '#7986CB',
    ],
];
$graphOptions = [
    'title' => ['text' => 'Calf Growth'],
    'subtitle' => ['text' => '12 Month trend'],
    'xAxis' => [
        'title' => [
            'text' => '',
            'style' => ['fontWeight' => 'normal'],
        ],
        'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Average Weight (Kilograms)',
            'style' => ['fontWeight' => 'normal'],
        ]
    ]
];
$containerId = 'chartContainerCalf';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>

