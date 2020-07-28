<?php

use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\dashboard\models\DataViz;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */

$region_id = Yii::$app->request->get('region_id', null);
$graph_type = Yii::$app->request->get('graph_type', DataViz::GRAPH_COLUMN);

?>
<div class="row">
    <div id="chartContainerAIPreg" style="width:100%;"></div>
</div>
<?php
$years = CountriesDashboardStats::rangeYears();
$res = CountriesDashboardStats::getCountryAvgAIPerPD($filterOptions['country_id'], $region_id);
$data = [];
foreach ($years as $year){
    $point = 0;
    foreach ($res as $row){
        if ($row['year'] == $year){
            $point = (float) $row['insemenation_avg'];
        }
    }
    if ($graph_type == DataViz::GRAPH_PIE){
        $data[] = [
            'name' => $year,
            'y' => (float) $point,
        ];
    }
    else {
        $data[] = (float) $point;
    }
}
//dd($res, $data);

$series = [
    [
        'name' => Country::getScalar('name', ['id' => $filterOptions['country_id']]),
        'type' => $graph_type,
        'data' => $data,
        'color' => '#771957',
        'zIndex' => 2,
    ],

];
$graphOptions = [
    'title' => ['text' => 'Average Insemination per Pregnancy'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'categories' => $years,
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Avg Insemination Count',
        ]
    ],
    'colors' => [
        '#AE2921', '#000000', '#004619',
        '#7F5298', '#7986CB', '#81D097',
    ],
    'legend' => [
        'labelFormatter' => new \yii\web\JsExpression("
            function () {
                var type = this.userOptions.type;
                if(type === 'line'){
                    return this.name;
                }
                else {
                    return this.name;
                }
            }
        ")
    ]
];
$containerId = 'chartContainerAIPreg';
if ($graph_type == DataViz::GRAPH_PIE){
    $this->registerJs("MyApp.modules.dashboard.piechart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");
}
else {
    $this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");
}
?>
