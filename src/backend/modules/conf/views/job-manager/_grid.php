<?php

use backend\modules\conf\models\Jobs;
use common\widgets\grid\GridView;
use common\helpers\Lang;
use common\helpers\Utils;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Jobs */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => true, 'modal' => true],
    'striped' => false,
    'rowOptions' => function (Jobs $model) {
        return ["class" => !$model->is_active ? "border border-danger" : ""];
    },
    'showExportButton' => false,
    'columns' => [
        [
            'attribute' => 'id',
            'filter' => false,
        ],
        [
            'attribute' => 'execution_type',
            'value' => function (Jobs $model) {
                return Jobs::decodeExecutionType($model->execution_type);
            },
            'filter' => false,
        ],
        [
            'attribute' => 'last_run',
            'value' => function (Jobs $model) {
                return \common\helpers\DateUtils::formatToLocalDate($model->last_run, "d/m/Y H:i:s UTC");
            },
            'filter' => false,
        ],
        [
            'attribute' => 'is_active',
            'filter' => Utils::booleanOptions(),
            'value' => function (Jobs $model) {
                return Html::tag('span', Utils::decodeBoolean($model->is_active), ['class' => $model->is_active ? 'badge badge-success' : 'badge badge-danger']);
            },
            'format' => 'raw',
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{start}{stop}{update}{delete}',
            'width' => '150px;',
            'buttons' => [
                'start' => function ($url, Jobs $model) {
                    if (Yii::$app->user->canUpdate() && !$model->is_active) {
                        return Html::a('<i class="fas fa-check-circle text-success"></i>', 'javascript:void(0);', ['title' => Lang::t('Start the process'), 'data-pjax' => 0, 'data-href' => $url, 'data-grid' => $model->getPjaxWidgetId(), 'class' => 'grid-update']);
                    } else {
                        return "";
                    }
                },
                'stop' => function ($url, Jobs $model) {
                    if (Yii::$app->user->canUpdate() && $model->is_active) {
                        return Html::a('<i class="fas fa-ban text-danger"></i>', 'javascript:void(0);', ['title' => Lang::t('Stop all processes'), 'data-pjax' => 0, 'data-href' => $url, 'data-grid' => $model->getPjaxWidgetId(), 'class' => 'grid-update']);
                    } else {
                        return "";
                    }
                },
            ]
        ],
    ],
]);
?>