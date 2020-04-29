<?php

use backend\modules\auth\Session;
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
$chart_data = CountriesDashboardStats::getAIForDataViz();
$data = [];
if (count($chart_data) > 0) {
    $values = [];
    foreach ($chart_data as $country => $country_data) {
        foreach ($country_data as $cdata){
            $values[$cdata['label']][] = $cdata['value'];
        }
    }
    foreach ($values as $t => $dv){
        // remove those with zeros for all countries
        $sum = array_sum($dv);
        if($sum > 0){
            $data[] = [
                'name' => $t,
                'data' => $dv,
            ];
        }
    }
}
/*
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
*/
$series = $data;
$graphOptions = [
    'chart' => [
        'type' => 'bar',
    ],
    'title' => ['text' => 'Total AI by Breeds'],
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
        'series' => [
            'stacking' => 'normal',
        ]
    ],
];
$containerId = 'chartContainerAI';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
