<?php

use backend\modules\core\models\OdkJsonQueue;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model OdkJsonQueue */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'columns' => [
        [
            'attribute' => 'uuid',
        ],
        [
            'attribute' => 'is_processed',
            'value' => function (OdkJsonQueue $model) {
                return Html::tag('span', Utils::decodeBoolean($model->is_processed), ['class' => $model->is_processed ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'filter' => false,
        ],
        [
            'attribute' => 'has_errors',
            'value' => function (OdkJsonQueue $model) {
                return Html::tag('span', Utils::decodeBoolean($model->has_errors), ['class' => $model->has_errors ? 'kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'filter' => false,
        ],
        [
            'attribute' => 'created_at',
            'value' => function (OdkJsonQueue $model) {
                return \common\helpers\DateUtils::formatToLocalDate($model->created_at);
            },
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{process}{view}{delete}',
            'viewOptions' => ['data-pjax' => 0, 'title' => 'View details', 'data-use-uuid' => true],
        ],
    ],
]);
?>