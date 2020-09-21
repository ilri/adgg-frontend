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
    '#fe0000', '#86cc31', '#61812e', '#94e2dd',
    '#0f767a', '#1be19f', '#0a60a8', '#d5d2e7',
    '#830c6f', '#cd49dc', '#ab6eaf', '#f6b0ec',
    '#3441c5', '#e3488e', '#562fff', '#d2c966',
    '#5e4028', '#fea53b', '#a07d62', '#20f53d',
    '#fe0000', '#b3e467', '#022114', '#cafafa',
    '#509d99', '#59faea', '#245a62', '#4cf185',
    '#2f882d', '#020b39', '#9baad8', '#2f158b',
    '#a17bf2', '#49406e', '#ef66f0', '#71114b',
    '#feafda', '#9a05cb', '#b66c96', '#88fe0e',
];
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
                $values[$cdata['label']][] = $cdata['value'];
            }
        }
        else{
            $empty_countries[] = $country;
        }
    }
    foreach ($values as $t => $dv){
        # shuffle colors
        if (!empty($colors)){
            $color_key = array_rand($colors);
            $color = $colors[$color_key];
            unset($colors[$color_key]);
            if (!array_key_exists($t, $breed_colors)){
                $breed_colors[$t] = $color;
            }
        }
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
$containerId = 'chartContainerBreeds';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
