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
$chart_data = CountriesDashboardStats::getCalfWeightGrowthForDataViz();
//dd($chart_data);
$data = [];
if (count($chart_data) > 0) {
    foreach ($chart_data as $country => $country_data) {
        $values = [];
        foreach ($country_data as $cdata){
            $values[] = $cdata['value'];
        }
        $data[] = [
            'name' => $country,
            'data' => $values,
        ];
    }
}
/*
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
*/
$series = $data;
$graphOptions = [
    'title' => ['text' => 'Calf Growth'],
    'subtitle' => ['text' => '12 Month trend'],
    'xAxis' => [
        'title' => [
            'text' => '',
            'style' => ['fontWeight' => 'normal'],
        ],
        'categories' =>
            array_map(function ($date){
                return \common\helpers\DateUtils::formatDate($date, 'M Y');
            }, CountriesDashboardStats::getDashboardDateCategories())
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

