<?php

use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
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
$chart_data = CountriesDashboardStats::getAnimalsByCategoriesForDataViz($filterOptions);
$colorOptions = CountriesDashboardStats::colorOptions();
$colors = [
    '#7D3701', '#641E16', '#27921E',
    '#EB6060', '#489661', '#C97434',
];
shuffle($colors);
$data = [];
$breed_colors = [];
$first_stack = null;
if (count($chart_data) > 0) {
    $first_stack = array_key_first($chart_data);
    foreach ($chart_data as $country => $country_data) {
        foreach ($country_data as $breed => $breed_data){
            $values = [];
            foreach ($breed_data as $year => $ydata){
                $values[] = $ydata['value'];
            }

            if (!isset($colorOptions['animal_types'])) {
                $colorOptions['animal_types'] = [];
            }
            $item = [
                'id' => $breed,
                'name' => $breed,
                'data' => $values,
                'stack' => $country,
                'color' => $colorOptions['animal_types'][$breed],
            ];
            if ($country != $first_stack){
                $item['linkedTo'] = $breed;
            }

            try {
                $data[] = $item;
            } catch (Exception $e) {
                print "Animal Type is not available";
            }

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
    'title' => ['text' => 'Categories of Animals registered by Year'],
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
    'colors' => $colors,
];
$containerId = 'chartContainerAC';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
