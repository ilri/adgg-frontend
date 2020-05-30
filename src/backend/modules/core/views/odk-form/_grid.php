<?php

use backend\modules\core\models\OdkForm;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model OdkForm */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => false, 'modal' => false],
    'columns' => [
        [
            'attribute' => 'form_uuid',
        ],
        [
            'attribute' => 'form_version',
        ],
        [
            'attribute' => 'is_processed',
            'value' => function (OdkForm $model) {
                return Html::tag('span', Utils::decodeBoolean($model->is_processed), ['class' => $model->is_processed ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'filter' => false,
        ],
        [
            'attribute' => 'has_errors',
            'value' => function (OdkForm $model) {
                return Html::tag('span', Utils::decodeBoolean($model->has_errors), ['class' => $model->has_errors ? 'kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'filter' => false,
        ],
        [
            'attribute' => 'error_message',
            'filter' => false,
        ],
        [
            'attribute' => 'created_at',
            'value' => function (OdkForm $model) {
                return \common\helpers\DateUtils::formatToLocalDate($model->created_at);
            },
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{process}{view}{delete}',
            'viewOptions' => ['data-pjax' => 0, 'title' => 'View details', 'data-use-uuid' => true],
            'width' => '200px;',
            'buttons' => [
                'process' => function ($url, OdkForm $model) {
                    return Html::a('Process JSON', '#', ['data-pjax' => 0, 'class' => 'grid-update', 'data-grid' => $model->getPjaxWidgetId(), 'data-href' => Url::to(['odk-form/process', 'id' => $model->id])]);
                }
            ]
        ],
    ],
]);
?>