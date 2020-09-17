<?php

use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $country_id int*/
?>
<style>
    .highcharts-stack-labels text {
        transform: translate(0, 3px);
        visibility: visible;
    }
</style>
<div class="row">
    <div id="chartContainerAC" style="width:100%;"></div>
</div>
<?php
$chart_data = CountriesDashboardStats::getAnimalsByCategoriesRegionsForDataViz($filterOptions, $country_id);
$colors = [
    '#800080', '#641E16', '#6298D7', '#2B7B48', '#9C0204',
    '#CD90C9', '#DCA8D9', '#EBC0E8',
    '#FAD8F7', '#000000', '#1E1E1E', '#363636',
    '#4F4F4F', '#6A6A6A', '#878787', '#A4A4A4',
    '#C3C3C3', '#E2E2E2', '#ECBEB3', '#FFD7CD',
    '#641E16', '#783429', '#8B4A3E', '#9F6054',
    '#B2776A', '#C58E82', '#D9A69A', '#C6E6FF',
];
$data = [];
$breed_colors = [];
$first_stack = null;
if (count($chart_data) > 0) {
    $first_stack = array_key_first($chart_data);
    foreach ($chart_data as $region => $region_data) {
        foreach ($region_data as $breed => $breed_data){
            $values = [];
            foreach ($breed_data as $year => $ydata){
                $values[] = $ydata['value'];
                if (!empty($colors)){
                    $color_key = array_rand($colors);
                    $color = $colors[$color_key];
                    unset($colors[$color_key]);
                    if (!array_key_exists($breed, $breed_colors)){
                        $breed_colors[$breed] = $color;
                    }
                }
            }
            $item = [
                'id' => $breed,
                'name' => $breed,
                'data' => $values,
                'stack' => $region,
                'color' => $breed_colors[$breed],
            ];
            if ($region != $first_stack){
                $item['linkedTo'] = $breed;
            }
            $data[] = $item;

        }
    }
}
//dd($chart_data, $breed_colors, $data);
/*$_series = [
    [
        'name' => 'Bull',
        'data' => [1,2,3,4,5],
        'stack' => 'KE',
    ],
    [
        'name' => 'Cow',
        'data' => [3,4,5,6,7],
        'stack' => 'KE',
    ],
    [
        'name' => 'Bull',
        'data' => [5,6,7,8,9],
        'stack' => 'ET',
    ],
    [
        'name' => 'Cow',
        'data' => [7,5,5,6,9],
        'stack' => 'ET',
    ],
    [
        'name' => 'Bull',
        'data' => [17,18,18,18,19],
        'stack' => 'TZ',
    ],
    [
        'name' => 'Cow',
        'data' => [14,12,13,15,11],
        'stack' => 'TZ',
    ],
];
*/
$series = $data;
$graphOptions = [
    'chart' => [
        'type' => 'column',
        'styledMode' => true,
    ],
    'title' => ['text' => 'Categories of Animals registered by Region'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        [
            'categories' => CountriesDashboardStats::rangeYears(),
            'labels' => [
                'autoRotation' => false,
                'style' => [
                    'fontSize' => '10px',
                    'align' => 'Right',
                    'textOverflow' => 'none',
                ],
                'y' => 30
            ],
            'tickWidth' => 0,
        ],
    ],
    'tooltip' => [
        'shared' => false,
        'formatter' => new \yii\web\JsExpression("
            function () {
            //console.log(this);
                return '<b>' + this.x +  ' ' + this.series.options.stack +  '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        "),

    ],
    'yAxis' => [
        'title' => [
            'text' => 'Number of animals',
            'style' => ['fontWeight' => 'normal'],
        ],
        'stackLabels' => [
            'enabled' => true,
            'y' => 10,
        ],
    ],
    'plotOptions' => [
        'column' => [
            'stacking' => 'normal',
            'groupPadding'  => 0.10,
        ]
    ],
    'colors' => [
        '#800080', '#641E16', '#6298D7', '#2B7B48', '#9C0204',
        '#CD90C9', '#DCA8D9', '#EBC0E8',
        '#FAD8F7', '#000000', '#1E1E1E', '#363636',
        '#4F4F4F', '#6A6A6A', '#878787', '#A4A4A4',
        '#C3C3C3', '#E2E2E2', '#ECBEB3', '#FFD7CD',
        '#641E16', '#783429', '#8B4A3E', '#9F6054',
        '#B2776A', '#C58E82', '#D9A69A', '#C6E6FF',
    ],
];
$containerId = 'chartContainerAC';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
