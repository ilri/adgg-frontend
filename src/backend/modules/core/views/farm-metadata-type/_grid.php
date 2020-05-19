<?php

use backend\modules\core\models\FarmMetadataType;
use common\helpers\Utils;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $model FarmMetadataType */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'columns' => [
        [
            'attribute' => 'name',
            'filter' => false,
        ],
        [
            'attribute' => 'model_class_name',
            'filter' => false,
        ],
        [
            'attribute' => 'farmer_has_multiple',
            'value' => function (FarmMetadataType $model) {
                return \yii\helpers\Html::tag('span', Utils::decodeBoolean($model->farmer_has_multiple), ['class' => $model->farmer_has_multiple ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'hiddenFromExport' => true,
            'filter' => Utils::booleanOptions(),
        ],
        [
            'attribute' => 'is_active',
            'value' => function (FarmMetadataType $model) {
                return \yii\helpers\Html::tag('span', Utils::decodeBoolean($model->is_active), ['class' => $model->is_active ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'hiddenFromExport' => true,
            'filter' => Utils::booleanOptions(),
        ],

        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{view}',
            'visibleButtons' => [
                'update' => function (\backend\modules\core\models\FarmMetadataType $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => true, 'data-use-uuid' => true],
            'viewOptions' => [
                'label' => '<i class="fa fa-eye"></i>',
                'data-pjax' => 0,
                'class' => 'show_modal_form',
            ],
        ],
    ],
]);
?>