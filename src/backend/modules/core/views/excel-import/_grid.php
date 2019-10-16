<?php

use backend\modules\core\models\ExcelImport;
use common\widgets\grid\GridView;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model ExcelImport */
?>
<?= GridView::widget([
    'searchModel' => $model,
    //'filterModel' => $model,
    'createButton' => ['visible' => false],
    'showExportButton' => false,
    'columns' => [
        [
            'attribute' => 'id',
        ],
        [
            'attribute' => 'type',
            'value' => function (ExcelImport $model) {
                return $model->getDecodedType();
            },
        ],
        [
            'attribute' => 'file_name',
            'value' => function (ExcelImport $model) {
                return Html::a($model->file_name, ['download-file', 'id' => $model->id], ['data-pjax' => 0]);
            },
            'format' => 'raw',
            'filter' => false,
        ],
        [
            'attribute' => 'is_processed',
            'format' => 'boolean',
        ],
        [
            'attribute' => 'has_errors',
            'format' => 'boolean',
        ],
        [
            'attribute' => 'error_csv',
            'value' => function (ExcelImport $model) {
                return Html::a($model->error_csv, ['download-error', 'id' => $model->id], ['data-pjax' => 0]);
            },
            'format' => 'raw',
            'filter' => false,
        ],
        [
            'attribute' => 'processing_duration_seconds',
            'format' => ['decimal'],
        ],
        [
            'attribute' => 'current_processed_row',
            'format' => ['decimal'],
        ],
        [
            'attribute' => 'created_at',
            'value' => function (ExcelImport $model) {
                return \common\helpers\DateUtils::formatToLocalDate($model->created_at);
            },
        ],
    ],
]);
?>