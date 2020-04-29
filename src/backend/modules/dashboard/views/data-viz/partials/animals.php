<?php

use backend\modules\auth\Session;
use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
?>
<div class="row">
    <div id="chartContainerAnimals" title="" style="width:100%;"></div>
</div>
<?php
$chart_data = CountriesDashboardStats::getRegisteredAnimalsForDataViz();
$data = [];
if (count($chart_data) > 0) {
    $values = [];
    foreach ($chart_data as $country => $country_data) {
        foreach ($country_data as $cdata){
            $values[$cdata['label']][] = $cdata['value'];
        }
    }
    foreach ($values as $t => $dv){
        $data[] = [
            'name' => $t,
            'data' => $dv,
        ];
    }
}
/*
$series = [
    [
        'name' => 'Calf',
        //'data' => $data,
        'data' => [10,12,13],
        'color' => '#771957',
    ],
    [
        'name' => 'Cow',
        //'data' => $data,
        'data' => [15,16,17],
        'color' => '#7F5298',
    ],
    [
        'name' => 'Bull',
        //'data' => $data,
        'data' => [14,15,15],
        'color' => '#7986CB',
    ],
];
*/
$series = $data;
$graphOptions = [
    'chart' => [
        'type' => 'column',
    ],
    'title' => ['text' => 'Registered Animals'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'title' => [
            'text' => (Session::isPrivilegedAdmin() || Session::isCountryUser()) ? 'Countries' : '',
            'style' => ['fontWeight' => 'normal'],
        ],
        'categories' => array_values(CountriesDashboardStats::getDashboardCountryCategories())
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Totals',
            'style' => ['fontWeight' => 'normal'],
        ]
    ],
    'plotOptions' => [
        'column' => [
            'pointPadding' => 0.1,
            'borderWidth' => 0,
        ]
    ],
];
$containerId = 'chartContainerAnimals';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
