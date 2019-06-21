<?php

use common\widgets\select2\Select2;

/* @var $this \yii\web\View */
/* @var $filterOptions array */
$idPrefix = 'farm-g-filter-';
?>
<div class="row">
    <div class="col-md-2">
        <?= Select2::widget([
            'name' => 'org_id',
            'value' => $filterOptions['org_id'] ?? null,
            'data' => \backend\modules\core\models\Organization::getListData(),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'org_id',
                'placeholder' => '--All Countries--',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-sm-2">
        <?= Select2::widget([
            'name' => \common\widgets\highchart\HighChart::GET_PARAM_GRAPH_TYPE,
            'value' => $graphType,
            'data' => \common\widgets\highchart\HighChart::graphTypeOptions(false, []),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'graph-type',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-sm-2">{date_range}</div>
    <div class="col-sm-1">{button}</div>
</div>