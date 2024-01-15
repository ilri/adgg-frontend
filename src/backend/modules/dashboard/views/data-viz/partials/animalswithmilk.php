<?php

use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $filterOptions array */
?>
<div class="row">
    <div id="chartContainerAM" style="width:100%;"></div>
</div>
<?php
$animals = CountriesDashboardStats::getAnimalsCumulativeForDataViz($filterOptions);
# this is what takes a lot of time ~17s
$animals_with_milk = CountriesDashboardStats::getAnimalsWithMilkForDataViz($filterOptions);
//dd($animals, $animals_with_milk);
$colors = [
    '#9EEDB3', '#1B4F72', '#001D00',
    '#5D84A5', '#771957', '#7F5298',
    '#350d36', '#65B27C', '#004619',
    '#D3E36F', '#C97434', '#AE2921',
    '#DBB450', '#27921E', '#0a60a8',
    '#C25D55', '#875F03',
    '#EBC0E8', '#1be19f',
    '#C6E6FF', '#022114', '#245a62',
    '#509d99', '#59faea',
    '#61812e', '#9baad8',
    '#e3488e', '#d2c966', '#2f158b',
    '#a07d62', '#20f53d', '#020b39',
    '#b3e467', '#4cf185', '#0f767a',
];
// one per country, this will be applied to the columns, for lines we will use the Color util to generate a dark or lighter shade for each
$country_colors = ['Kenya' => '#875F03', 'Tanzania' => '#65B27C', 'Ethiopia' => '#5D84A5','Uganda' => '#FCDC04', 'Nigeria' => '#008000','Zambia' => '#00FF00','Nepal' => '#2f158b', 'Burundi'=>'#022114'];

$data = [];

if (count($animals) > 0) {
    foreach ($animals as $country => $country_data) {
        $values = [];
        foreach ($country_data as $cdata){
            $values[] = $cdata['value'];
        }
        $data[] = [
            'name' => $country,
            'type' => 'column',
            'data' => $values,
            'color' => $country_colors[$country],
        ];
    }
}
if (count($animals_with_milk) > 0) {
    foreach ($animals_with_milk as $country => $country_data) {
        $values = [];
        foreach ($country_data as $cdata){
            $values[] = $cdata['value'];
        }
        // generate a color which is a shade of the country color
        $color = new \common\helpers\Color($country_colors[$country]);
        $line_color = (string) $color;
        // if it's dark or light, generate shades for each breed
        if ($color->isLight()){
            $line_color = $color->darken(\common\helpers\Color::DEFAULT_ADJUST + 12);
        }
        else {
            $line_color = $color->lighten(\common\helpers\Color::DEFAULT_ADJUST + 12);
        }
        $data[] = [
            'name' => $country,
            'type' => 'line',
            'data' => $values,
            'zIndex' => 2,
            'color' =>  '#'.$line_color,
        ];
    }
}
//dd($data);
/*
$_series = [
    [
        'name' => 'Kenya',
        'type' => 'line',
        'data' => [1,2,3,4,5,6,7,8,9,10,11,12],
        'color' => '#771957',
        'zIndex' => 2,
    ],
    [
        'name' => 'Ethiopia',
        'type' => 'line',
        'data' => [5,6,7,8,9,10,12,13,14,15,17,19],
        'color' => '#7986CB',
        'zIndex' => 2,
    ],
    [
        'name' => 'Tanzania',
        'type' => 'line',
        'data' => [17,18,18,18,19,21,22,23,24,25,27,29],
        'color' => '#7F5298',
        'zIndex' => 2,
    ],
    [
        'name' => 'Kenya',
        'type' => 'column',
        'data' => [10,12,13,14,15,16,17,18,19,20,21,22],
        'color' => '#056030',
    ],
    [
        'name' => 'Ethiopia',
        'type' => 'column',
        'data' => [15,16,17,18,19,20,22,23,25,27,28,30],
        'color' => '#2B7B48',
    ],
    [
        'name' => 'Tanzania',
        'type' => 'column',
        'data' => [14,15,17,18,20,23,24,26,28,29,32,25],
        'color' => '#489661',
    ],
];
*/
$series = $data;
$graphOptions = [
    'title' => ['text' => 'Animals Registered and Monitored for Milk Production'],
    'subtitle' => ['text' => ''],
    'xAxis' => [
        'categories' =>
            array_map(function (stdClass $quarter){return $quarter->period;}, CountriesDashboardStats::getQuarters()),
    ],
    'yAxis' => [
        'title' => [
            'text' => 'Number of animals',
        ]
    ],
    'colors' => $colors,
    'legend' => [
        'labelFormatter' => new \yii\web\JsExpression("
            function () {
                var type = this.userOptions.type;
                if(type === 'line'){
                    return this.name + ' (Animals monitored for milk production)';
                }
                else {
                    return this.name + ' (Animals registered)';
                }
            }
        ")
    ]
];
$containerId = 'chartContainerAM';
$this->registerJs("MyApp.modules.dashboard.chart('" . $containerId . "', " . Json::encode($series) . "," . Json::encode($graphOptions) . ");");

?>
