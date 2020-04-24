<?php

use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
?>
<div class="row">
    <div id="chartContainerAC" style="width:100%;"></div>
</div>
<?php
$chart_data = CountriesDashboardStats::getAnimalsByCategoriesForDataViz($filterOptions);

$data = [];
if (count($chart_data) > 0) {
    foreach ($chart_data as $country => $country_data) {
        foreach ($country_data as $breed => $breed_data){
            $values = [];
            foreach ($breed_data as $year => $ydata){
                $values[] = $ydata['value'];
            }
            $data[] = [
                'name' => $breed,
                'data' => $values,
                'stack' => $country,
            ];
        }
        /*$data[] = [
            'name' => $country,
            'data' => array_sum($values) < 1 ? $values : array_map(function ($value){
                return $value > 0 ? $value : null;
            }, $values),
        ];
        */
    }
}
//dd(CountriesDashboardStats::rangeYears(), $chart_data, $data);
$_series = [
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
$series = $data;
$graphOptions = [
    'chart' => [
        'type' => 'column',
    ],
    'title' => ['text' => 'Categories of Animals registered by Year'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        [
            'categories' => [2016,2017,2018,2019,2020],
            'labels' => [
                'autoRotation' => false,
                'style' => [
                    'fontSize' => '10px',
                    'align' => 'Right',
                    'textOverflow' => 'none',
                ],
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
        ]
    ],
    'plotOptions' => [
        /*'series' => [
            'stacking' => 'normal',
        ],*/
        'column' => [
            'stacking' => 'normal',
            'groupPadding'  => 0.10,
        ]
    ],
    'colors' => [
        '#771957',
        '#7986CB',
        '#7F5298',
    ],
];
$containerId = 'chartContainerAC';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
