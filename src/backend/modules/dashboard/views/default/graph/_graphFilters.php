<?php

use backend\modules\core\models\ListType;
use backend\modules\core\models\LookupList;
use backend\modules\core\models\Organization;
use common\widgets\highchart\HighChart;
use common\widgets\select2\Select2;

/* @var $this \yii\web\View */
/* @var $filterOptions array */
$idPrefix = 'animal-g-filter-';
?>
<div class="row">
    <div class="col-md-2">
        <?= Select2::widget([
            'name' => 'animal_type',
            'value' => $filterOptions['animal_type'] ?? null,
            'data' => LookupList::getList(ListType::LIST_TYPE_ANIMAL_TYPES, '--All animals-'),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'animal_type',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-md-2">
        <?= Select2::widget([
            'name' => 'org_id',
            'value' => $filterOptions['org_id'] ?? null,
            'data' => Organization::getListData('id', 'name', '--All Countries--'),
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => [
                'id' => $idPrefix . 'org_id',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class="col-sm-2">
        <?= Select2::widget([
            'name' => HighChart::GET_PARAM_GRAPH_TYPE,
            'value' => $graphType,
            'data' => HighChart::graphTypeOptions(false, []),
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