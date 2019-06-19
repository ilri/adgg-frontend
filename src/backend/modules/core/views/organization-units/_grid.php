<?php

use backend\modules\core\models\OrganizationUnits;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model OrganizationUnits */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'columns' => [
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'contact_name',
        ],
        [
            'attribute' => 'contact_phone',
        ],
        [
            'attribute' => 'contact_email',
            'format' => 'email',
        ],
        [
            'attribute' => 'parent_id',
            'label' => $model->getAttributeLabel('parent_id'),
            'value' => function (OrganizationUnits $model) {
                return $model->getRelationAttributeValue('parent', 'name');
            },
            'visible' => $model->level > OrganizationUnits::LEVEL_UNIT_1,
            'filter' => OrganizationUnits::getListData('id', 'name', false, ['level' => $model->level - 1, 'org_id' => $model->org_id])
        ],
        [
            'attribute' => 'is_active',
            'value' => function (OrganizationUnits $model) {
                return Html::tag('span', Utils::decodeBoolean($model->is_active), ['class' => $model->is_active ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}',
            'visibleButtons' => [
                'update' => function (OrganizationUnits $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => true, 'data-use-uuid' => true],
        ],
    ],
]);
?>