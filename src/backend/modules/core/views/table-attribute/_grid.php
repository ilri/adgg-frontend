<?php

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
        'url' => Url::to(['table-attribute/create', 'table_id' => $model->table_id]),
        'label' => '<i class="fa fa-plus-circle"></i> ' . Lang::t('Add Attribute'),
    ],
    'showExportButton' => false,
    'columns' => [
        [
            'attribute' => 'attribute_key',
        ],
        [
            'attribute' => 'attribute_label',
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
            'controller' => 'table-attribute',
        ],
    ],
]);
?>