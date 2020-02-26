<?php

use backend\modules\core\models\Organization;
use common\helpers\Utils;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $model Organization */
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
            'attribute' => 'country_id',
            'filter' => false,
            'value' => function (\backend\modules\core\models\Organization $model) {
                return $model->getRelationAttributeValue('country', 'name');
            },
        ],
        [
            'attribute' => 'is_active',
            'value' => function (Organization $model) {
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
                'update' => function (\backend\modules\core\models\Organization $model) {
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