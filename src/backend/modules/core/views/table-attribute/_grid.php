<?php

use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\FarmMetadataType;
use backend\modules\core\models\TableAttribute;
use backend\modules\core\models\TableAttributesGroup;
use common\helpers\Lang;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $filterOptions array */
/* @var $model TableAttribute */
?>
<?= GridView::widget([
    'title' => Lang::t('Table Attributes'),
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => [
        'visible' => Yii::$app->user->canCreate(),
        'modal' => true,
        'url' => Url::to(['table-attribute/create', 'table_id' => $model->table_id, 'event_type' => $model->event_type]),
        'label' => '<i class="fa fa-plus-circle"></i> ' . Lang::t('Add Attribute'),
    ],
    'showExportButton' => false,
    'columns' => [
        [
            'attribute' => 'id',
        ],
        [
            'attribute' => 'attribute_key',
        ],
        [
            'attribute' => 'attribute_label',
        ],
        [
            'attribute' => 'event_type',
            'value' => function (TableAttribute $model) {
                return AnimalEvent::decodeEventType($model->event_type);
            },
            'visible' => $model->table_id == TableAttribute::TABLE_ANIMAL_EVENT,
            'filter' => AnimalEvent::eventTypeOptions(),
        ],
        [
            'attribute' => 'farm_metadata_type',
            'value' => function (TableAttribute $model) {
                return $model->getRelationAttributeValue('farmMetadataType', 'name');
            },
            'visible' => $model->table_id == TableAttribute::TABLE_FARM_METADATA,
            'filter' => FarmMetadataType::getListData('code', 'name', false),
        ],
        [
            'attribute' => 'group_id',
            'value' => function (TableAttribute $model) {
                return $model->getRelationAttributeValue('group', 'name');
            },
            'filter' => TableAttributesGroup::getListData('id', 'name', false, ['table_id' => $model->table_id]),
        ],
        [
            'attribute' => 'input_type',
            'value' => function (TableAttribute $model) {
                return $model->getDecodedInputType();
            },
            //'filter' => TableAttribute::inputTypeOptions(),
            'filter' => false,
        ],
        [
            'attribute' => 'list_type_id',
            'value' => function (TableAttribute $model) {
                return $model->getRelationAttributeValue('listType', 'name');
            },
            'filter' => false,
        ],
        [
            'attribute' => 'odk_attribute_name',
            'filter' => false,
        ],
        [
            'attribute' => 'default_value',
            'value' => function (TableAttribute $model) {
                return $model->default_value;
            },
            'filter' => false,
        ],
        [
            'attribute' => 'is_active',
            'value' => function (TableAttribute $model) {
                return Utils::decodeBoolean($model->is_active);
            },
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{delete}',
            'visibleButtons' => [
                'delete' => function () {
                    return \backend\modules\auth\Session::isDev();
                }
            ],
            'controller' => 'table-attribute',
        ],
    ],
]);
?>