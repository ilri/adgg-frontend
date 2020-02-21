<?php

use backend\modules\core\models\CountryRef;
use common\helpers\Utils;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $model CountryRef */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'columns' => [
        [
            'attribute' => 'iso2',
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'call_code',
        ],
        [
            'attribute' => 'currency',
            'filter' => \backend\modules\core\models\Currency::getListData()
        ],
        [
            'attribute' => 'is_active',
            'value' => function (CountryRef $model) {
                return \yii\helpers\Html::tag('span', Utils::decodeBoolean($model->is_active), ['class' => $model->is_active ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'hiddenFromExport' => true,
            'filter' => Utils::booleanOptions(),
        ],
        [
            'attribute' => 'is_active',
            'value' => function (CountryRef $model) {
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