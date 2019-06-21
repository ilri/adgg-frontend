<?php

use common\widgets\highchart\HighChart;

/* @var $this \yii\web\View */
/* @var $graphFilterOptions array */

$graphType = $graphType ?? HighChart::GRAPH_LINE;
$dateRange = $dateRange ?? HighChart::getDefaultDateRange('Y/m/d');
?>

<?= HighChart::widget([
    'modelClass' => \backend\modules\reports\models\FarmReport::class,
    'graphType' => $graphType,
    'chartIndex' => 1,
    'graphTitle' => 'Farms stats',
    'yAxisLabel' => 'Total Farms',
    'chartTemplate' => $this->render('_template', ['filterOptions' => $graphFilterOptions, 'graphType' => $graphType]),
    'filterFormAction' => ['graph'],
    'htmlOptions' => ['class' => ''],
    'filterFormTemplate' => $this->render('_graphFilters', ['filterOptions' => $graphFilterOptions, 'graphType' => $graphType]),
    'multipleAxis' => false,
    'showSummaryStats' => true,
    'summaryStatsWrapperId' => 'farms-summary-stats',
    'summaryStatsData' => function () {
        /* @var $this HighChart */
        $stats = $this->getView()->render('_stats', ['filters' => $this->queryOptions['filters'] ?? null]);
        return $stats;
    },
    'queryOptions' => [
        'condition' => '',
        'params' => [],
        'filters' => $graphFilterOptions,
        'dateField' => 'reg_date',
        'dateRange' => $dateRange,//date range of query eg '2015-01-01 - 2015-12-31'
        'sum' => false,
        'enforceDate' => false,
    ],
]) ?>