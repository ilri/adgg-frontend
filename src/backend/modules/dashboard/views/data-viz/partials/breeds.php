<?php

use backend\modules\auth\Session;
use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
?>
<div class="row">
    <div id="chartContainerBreeds" title="" style="width:100%;"></div>
</div>
<?php
$chart_data = CountriesDashboardStats::getAnimalsByBreedsForDataViz();
$data = [];
$empty_countries = [];
$countries = [];
//dd($chart_data, $data);
if (count($chart_data) > 0) {
    $values = [];
    foreach ($chart_data as $country => $country_data) {
        if (count($country_data)){
            $countries[] = $country;
            foreach ($country_data as $cdata){
                $values[$cdata['label']][] = $cdata['value'];
            }
        }
        else{
            $empty_countries[] = $country;
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
//dd($countries,$empty_countries,$data);
/*
$series = [
    [
        'name' => 'Fresian',
        'data' => [10,12,13],
        'color' => '#771957',
    ],
    [
        'name' => 'Zebu',
        'data' => [15,16,17],
        'color' => '#7F5298',
    ],
    [
        'name' => 'Borana',
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
    'title' => ['text' => 'Types of Breeds kept per Country'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'title' => [
            'text' => (Session::isPrivilegedAdmin() || Session::isCountryUser()) ? 'Countries' : '',
            'style' => ['fontWeight' => 'normal'],
        ],
        'categories' => array_merge($countries, $empty_countries),
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
$containerId = 'chartContainerBreeds';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
