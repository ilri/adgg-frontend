<?php

use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */

$region_id = Yii::$app->request->get('region_id', null);

?>
<div class="row">
    <div id="chartContainerAIPreg" style="width:100%;"></div>
</div>
<?php

$data = [];

$series = [
    [
        'name' => Country::getScalar('name', ['id' => $filterOptions['country_id']]),
        'type' => 'column',
        'data' => [0,0,0,0,0],
        'color' => '#771957',
        'zIndex' => 2,
    ],

];
$graphOptions = [
    'title' => ['text' => 'Average Insemination per Pregnancy'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'categories' => CountriesDashboardStats::rangeYears(),
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
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
