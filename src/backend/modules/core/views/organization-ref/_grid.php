<?php

use backend\modules\core\models\OrganizationRef;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model OrganizationRef */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'columns' => [
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'contact_person',
        ],
        [
            'attribute' => 'contact_phone',
        ],
        [
            'attribute' => 'contact_email',
            'format' => 'email',
        ],
        [
            'attribute' => 'dialing_code',
        ],
        [
            'attribute' => 'code',
        ],
        [
            'attribute' => 'is_active',
            'value' => function (OrganizationRef $model) {
                return Html::tag('span', Utils::decodeBoolean($model->is_active), ['class' => $model->is_active ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{view}{update}',
            'visibleButtons' => [
                'update' => function (OrganizationRef $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false, 'data-use-uuid' => true],
        ],
    ],
]);
?>