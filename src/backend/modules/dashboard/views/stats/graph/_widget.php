<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use backend\modules\reports\Constants;
use common\widgets\highchart\HighChart;

/* @var $this \yii\web\View */
/* @var $graphFilterOptions array */

$graphType = $graphType ?? HighChart::GRAPH_LINE;
$dateRange = $dateRange ?? HighChart::getDefaultDateRange('Y/m/d');
$groupBy = $groupBy ?? array_key_first(Constants::animalGraphGroupByOptions(false));
?>

<?= HighChart::widget([
    'modelClass' => \backend\modules\reports\models\FarmReport::class,
    'graphType' => $graphType,
    'chartIndex' => 1,
    'graphTitle' => 'Farmers Per Region',
    //'yAxisLabel' => 'Total animals',
    'chartTemplate' => $this->render('_template', ['filterOptions' => $graphFilterOptions, 'graphType' => $graphType]),
    'filterFormAction' => ['graph'],
    'htmlOptions' => ['class' => ''],
    'filterFormTemplate' => '',
    'multipleAxis' => false,
    'showSummaryStats' => true,
    'summaryStatsWrapperId' => 'animal-summary-stats',
    'summaryStatsData' => function () {
        /* @var $this HighChart */
        $stats = $this->getView()->render('_stats', ['filters' => $this->queryOptions['filters'] ?? null]);
        //return ;
    },
    'queryOptions' => [
        'condition' => '',
        'params' => [],
        'filters' => $graphFilterOptions,
        'dateField' => 'created_at',
        'dateRange' => null,//date range of query eg '2015-01-01 - 2015-12-31'
        'groupBy' => Yii::$app->request->get('groupBy', $groupBy),
        'sum' => false,
        'enforceDate' => false,
    ],
]) ?>