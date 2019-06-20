<?php

use backend\modules\core\models\Client;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model Client */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'rowOptions' => function (Client $model) {
        return ["class" => "linkable", "data-href" => Url::to(['view', "id" => $model->uuid])];
    },
    'columns' => [
        [
            'attribute' => 'farm_id',
            'value' => function (Client $model) {
                return $model->getRelationAttributeValue('farm', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'phone',
        ],
        [
            'attribute' => 'org_id',
            'value' => function (Client $model) {
                return $model->getRelationAttributeValue('org', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'region_id',
            'value' => function (Client $model) {
                return $model->getRelationAttributeValue('region', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'district_id',
            'value' => function (Client $model) {
                return $model->getRelationAttributeValue('district', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'ward_id',
            'value' => function (Client $model) {
                return $model->getRelationAttributeValue('ward', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'village_id',
            'value' => function (Client $model) {
                return $model->getRelationAttributeValue('village', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'gender_code',
            'hidden' => true,
        ],
        [
            'attribute' => 'is_active',
            'value' => function (Client $model) {
                return \common\helpers\Utils::decodeBoolean($model->is_active);
            },
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{view}',
            'visibleButtons' => [
                'update' => function (Client $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false, 'data-use-uuid' => true],
        ],
    ],
]);
?>