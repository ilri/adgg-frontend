<?php

use backend\modules\auth\Session;
use backend\modules\core\models\FarmMetadataType;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $model FarmMetadataType */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Session::isDev(), 'modal' => true],
    'showExportButton' => false,
    'columns' => [
        [
            'attribute' => 'code',
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'model_class_name',
            'filter' => false,
        ],
        [
            'attribute' => 'farmer_has_multiple',
            'value' => function (FarmMetadataType $model) {
                return Html::tag('span', Utils::decodeBoolean($model->farmer_has_multiple), ['class' => $model->farmer_has_multiple ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'hiddenFromExport' => true,
            'filter' => false,
        ],
        [
            'attribute' => 'is_active',
            'value' => function (FarmMetadataType $model) {
                return Html::tag('span', Utils::decodeBoolean($model->is_active), ['class' => $model->is_active ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'hiddenFromExport' => true,
            'filter' => Utils::booleanOptions(),
        ],

        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{view}',
            'visibleButtons' => [
                'update' => function (FarmMetadataType $model) {
                    return Session::isDev();
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