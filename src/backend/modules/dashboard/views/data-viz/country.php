<?php

use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use backend\modules\dashboard\models\DataViz;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $filterOptions array */

$controller = Yii::$app->controller;
$this->title = Lang::t('{country} Dashboard', ['country' => Country::getScalar('name', ['id' => $filterOptions['country_id']])]);
$this->params['breadcrumbs'] = [
    $this->title,
];

?>
<?= $this->render('country/_tab', []) ?>
<div class="tab-content">
    <div class="tab-pane" id="summaries" role="tabpanel">
        <?= $this->render('country/tabs/_summaries', [
                'filterOptions' => $filterOptions
        ]) ?>
    </div>
    <div class="tab-pane active" id="performance" role="tabpanel">
        <?= $this->render('country/tabs/_performance', [
                'filterOptions' => $filterOptions
        ]) ?>
    </div>
    <div class="tab-pane" id="service" role="tabpanel">
        <?= $this->render('country/tabs/_service', [
                'filterOptions' => $filterOptions
        ]) ?>
    </div>
    <div class="tab-pane" id="genetic" role="tabpanel">
        <?= $this->render('country/tabs/_genetic', [
                'filterOptions' => $filterOptions
        ]) ?>
    </div>
</div>


<?php
$options = [
    'ajaxAction' => Url::to(array_merge(['data-viz/load-chart', 'is_country' => true], $filterOptions)),
    'ajaxCharts' => [
        [
            'name' => 'country_summary',
            'renderContainer' => '#summaries_boxes'
        ],
        [
            'name' => 'country_breeds',
            'renderContainer' => '#summaries_breeds_by_region'
        ],
        [
            'name' => 'country_animalsbycategories',
            'renderContainer' => '#summaries_categories_by_region',
        ],
        [
            'name' => 'country_table',
            'renderContainer' => '#summaries_table_by_region'
        ],
        [
            'name' => 'fertility',
            'renderContainer' => '#fertility'
        ],
        [
            'name' => 'avg_body_weight',
            'renderContainer' => '#avg_body_weight'
        ],
        [
            'name' => 'avg_milk_yield',
            'renderContainer' => '#avg_milk_yield'
        ],
        [
            'name' => 'avg_ai_preg',
            'renderContainer' => '#avg_ai_preg'
        ],
        [
            'name' => 'ai_per_breed',
            'renderContainer' => '#ai_per_breed'
        ],

    ]
];
$this->registerJs("MyApp.modules.dashboard.dataviz(" . Json::encode($options) . ");");
?>
