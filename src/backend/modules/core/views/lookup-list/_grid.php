<?php

use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Choices;
use common\helpers\Utils;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $model Choices */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'showExportButton' => true,
    'columns' => [
        [
            'attribute' => 'id',
            'visible' => false,
        ],
        [
            'attribute' => 'list_type_id',
            'value' => function (Choices $model) {
                return $model->getRelationAttributeValue('listType', 'name');
            },
            'filter' => ChoiceTypes::getListData(),
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
            'value' => function (Choices $model) {
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