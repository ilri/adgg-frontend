<?php

use backend\modules\core\models\ListType;
use backend\modules\core\models\LookupList;
use common\helpers\Utils;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $model LookupList */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'showExportButton' => false,
    'columns' => [
        [
            'attribute' => 'id',
        ],
        [
            'attribute' => 'list_type_id',
            'value' => function (LookupList $model) {
                return $model->getRelationAttributeValue('listType', 'name');
            },
            'filter' => ListType::getListData(),
        ],
        [
            'attribute' => 'value',
        ],
        [
            'attribute' => 'label',
        ],
        [
            'attribute' => 'description',
        ],
        [
            'attribute' => 'is_active',
            'value' => function (LookupList $model) {
                return Utils::decodeBoolean($model->is_active);
            },
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{delete}',
        ],
    ],
]);
?>