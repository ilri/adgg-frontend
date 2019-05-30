<?php

use backend\modules\core\models\Currency;
use common\helpers\Utils;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $model Currency */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'columns' => [
        [
            'attribute' => 'id',
            'visible' => false,
        ],
        [
            'attribute' => 'iso3',
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'is_active',
            'value' => function (Currency $model) {
                return \yii\helpers\Html::tag('span', Utils::decodeBoolean($model->is_active), ['class' => $model->is_active ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'hiddenFromExport' => true,
            'filter' => Utils::booleanOptions(),
        ],
        [
            'attribute' => 'is_active',
            'value' => function (Currency $model) {
                return Utils::decodeBoolean($model->is_active);
            },
            'hidden' => true,
            'filter' => false,
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}',
        ],
    ],
]);
?>