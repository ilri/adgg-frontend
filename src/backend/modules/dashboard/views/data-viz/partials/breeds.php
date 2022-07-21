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
$colors = [
    '#9EEDB3', '#1B4F72', '#001D00',
    '#5D84A5', '#771957', '#7F5298',
    '#350d36', '#65B27C', '#004619',
    '#D3E36F', '#C97434', '#AE2921',
    '#DBB450', '#27921E', '#0a60a8',
    '#C25D55', '#875F03', '#7F5298',
    '#EBC0E8', '#1be19f', '#CCE5FF',
    '#C6E6FF', '#022114', '#245a62',
    '#509d99', '#59faea', '#CCFFE5',
    '#61812e', '#9baad8', '#FFE5CC',
    '#e3488e', '#d2c966', '#2f158b',
    '#a07d62', '#20f53d', '#020b39',
    '#b3e467', '#4cf185', '#0f767a',
];
shuffle($colors);
$empty_countries = [];
$breed_colors = [];
$countries = [];
//dd($chart_data, $data);
if (count($chart_data) > 0) {
    $values = [];
    foreach ($chart_data as $country => $country_data) {
        if (count($country_data)){
            $countries[] = $country;
            foreach ($country_data as $cdata){
                $breed_colors[$cdata['label']] = $cdata['color'];
                $values[$cdata['label']][] = $cdata['value'];
            }
        }
        else{
            $empty_countries[] = $country;
        }
    }
    ksort($values);
    //dd($values);
    foreach ($values as $t => $dv){
        // remove those with zeros for all countries
        $sum = array_sum($dv);
        if($sum > 0){
            $data[] = [
                'name' => $t,
                'data' => $dv,
                'color' => $breed_colors[$t],
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
    'colors' => $colors,
];
$containerId = 'chartContainerBreeds';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
