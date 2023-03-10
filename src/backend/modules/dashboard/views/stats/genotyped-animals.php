<?php
/* @var $this yii\web\View */

use backend\controllers\BackendController;
use backend\modules\core\models\Country;
use common\helpers\Lang;
use common\widgets\highchart\HighChart;

/* @var $controller BackendController */
/* @var $graphFilterOptions array */
/* @var $country Country */
$controller = Yii::$app->controller;
$this->title = Lang::t('Genotype Report');
$this->params['breadcrumbs'] = [
    $this->title,
];
$graphType = $graphType ?? HighChart::GRAPH_PIE;
?>
