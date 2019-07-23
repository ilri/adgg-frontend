<?php

use backend\modules\auth\models\UserLevels;
use common\helpers\Utils;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $model UserLevels */

?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'toolbarButtons' => [],
    'columns' => [
        [
            'attribute' => 'id',
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'parent_id',
            'value' => function (UserLevels $model) {
                return $model->getRelationAttributeValue('parent', 'name');
            },
            'filter' => false,
        ],
        [
            'attribute' => 'is_active',
            'format' => 'boolean',
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}',
            'buttons' => [],
        ],
    ],
]);
?>