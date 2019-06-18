<?php

use backend\modules\core\models\Organization;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model Organization */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'columns' => [
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'code',
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
            'attribute' => 'is_active',
            'value' => function (Organization $model) {
                return Utils::decodeBoolean($model->is_active);
            },
            'filter' => Utils::booleanOptions(),
        ],
        [
            'attribute' => 'is_active',
            'value' => function (Organization $model) {
                return Html::tag('span', Utils::decodeBoolean($model->is_active), ['class' => $model->is_active ? 'kt-badge  kt-badge--success kt-badge--inline kt-badge--pill' : 'kt-badge  kt-badge--metal kt-badge--inline kt-badge--pill']);
            },
            'format' => 'raw',
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{view}{update}{delete}',
            'visibleButtons' => [
                'update' => function (Organization $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false, 'data-use-uuid' => true],
        ],
    ],
]);
?>