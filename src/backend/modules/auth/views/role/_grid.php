<?php

use backend\modules\auth\models\Roles;
use backend\modules\auth\Session;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model Roles */

?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'toolbarButtons' => [],
    'rowOptions' => function (Roles $model) {
        return ["class" => "linkable", "data-href" => Url::to(['view', "id" => $model->id])];
    },
    'columns' => [
        [
            'attribute' => 'name',
            'filter' => false,
        ],
        [
            'attribute' => 'description',
            'filter' => false,
            'visible' => false,
        ],
        [
            'attribute' => 'level_id',
            'value' => function (Roles $model) {
                return $model->getRelationAttributeValue('level', 'name');
            },
            'filter' => false,
            'visible' => Session::isDev(),
        ],
        [
            'attribute' => 'is_active',
            'value' => function (Roles $model) {
                return Utils::decodeBoolean($model->is_active);
            },
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{view}{update}',
            'viewOptions' => ['label' => 'Update Privileges'],
            'width' => '200px',
            'visibleButtons' => [
                'update' => function (Roles $model) {
                    return Yii::$app->user->canUpdate() && !Session::isCountry();
                }
            ],
        ],
    ],
]);
?>