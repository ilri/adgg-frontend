<?php

use backend\modules\core\models\Farm;
use common\helpers\Lang;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model Farm */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'toolbarButtons' => [
        Yii::$app->user->canCreate() ? '<a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to(array_merge(['upload'], Yii::$app->request->queryParams)) . '" data-pjax="0"><i class="fa fa-file-excel-o"></i> ' . Lang::t('Upload Excel/CSV') . '</a> ' : '',
    ],
    'rowOptions' => function (Farm $model) {
        return ["class" => "linkable", "data-href" => Url::to(['view', "id" => $model->uuid])];
    },
    'columns' => [
        [
            'attribute' => 'code',
            'hidden' => true,
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'phone',
        ],
        [
            'attribute' => 'email',
            'hidden' => true,
        ],
        [
            'attribute' => 'reg_date',
            'value' => function (Farm $model) {
                \common\helpers\DateUtils::formatDate($model->reg_date, 'd-M-Y');
            },
            'hidden' => true,
        ],
        [
            'attribute' => 'org_id',
            'value' => function (Farm $model) {
                return $model->getRelationAttributeValue('org', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'region_id',
            'value' => function (Farm $model) {
                return $model->getRelationAttributeValue('region', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'district_id',
            'value' => function (Farm $model) {
                return $model->getRelationAttributeValue('district', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'ward_id',
            'value' => function (Farm $model) {
                return $model->getRelationAttributeValue('ward', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'village_id',
            'value' => function (Farm $model) {
                return $model->getRelationAttributeValue('village', 'name');
            },
            'hidden' => false,
        ],
        [
            'attribute' => 'field_agent_name',
        ],
        [
            'attribute' => 'farm_type',
            'hidden' => false,
        ],
        [
            'attribute' => 'gender_code',
            'hidden' => true,
        ],
        [
            'attribute' => 'project',
            'hidden' => false,
        ],
        [
            'attribute' => 'is_active',
            'format' => 'boolean',
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{view}',
            'visibleButtons' => [
                'update' => function (Farm $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false, 'data-use-uuid' => true],
        ],
    ],
]);
?>