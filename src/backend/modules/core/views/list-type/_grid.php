<?php

use backend\modules\core\models\ListType;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $model ListType */
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
            'attribute' => 'name',
        ],
        [
            'attribute' => 'description',
            'filter' => false,
            'visible' => false,
        ],
        [
            'attribute' => 'is_active',
            'value' => function (ListType $model) {
                return Utils::decodeBoolean($model->is_active);
            },
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{view-list}',
            'width' => '200px;',
            'buttons' => [
                'view-list' => function ($url, ListType $model) {
                    return Html::a('View Lists', ['lookup-list/index', 'list_type_id' => $model->id], ['data-pjax' => 0]);
                }
            ]
        ],
    ],
]);
?>