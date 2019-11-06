<?php

use backend\modules\core\models\Animal;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model Animal */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'rowOptions' => function (Animal $model) {
        return ["class" => "linkable", "data-href" => Url::to(['view', "id" => $model->uuid])];
    },
    'toolbarButtons' => [
        Yii::$app->user->canCreate() ? '<a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to(array_merge(['upload'], Yii::$app->request->queryParams)) . '" data-pjax="0"><i class="fa fa-file-excel-o"></i> ' . Lang::t('Upload Excel/CSV') . '</a> ' : '',
    ],
    'columns' => [
        [
            'attribute' => 'tag_id',
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'farm_id',
            'value' => function (Animal $model) {
                return $model->getRelationAttributeValue('farm', 'name');
            },
            'filter' => false,
        ],
        [
            'attribute' => 'animal_type',
            'value' => function (Animal $model) {
                return $model->animal_type;
            }
        ],
        [
            'attribute' => 'main_breed',
            'value' => function (Animal $model) {
                return $model->main_breed;
            }
        ],
        [
            'attribute' => 'sire_tag_id',
        ],
        [
            'attribute' => 'sire_name',
            'value' => function (Animal $model) {
                return $model->getRelationAttributeValue('sire', 'name',$model->sire_name);
            },
            'filter' => false,
        ],
        [
            'attribute' => 'dam_tag_id',
        ],
        [
            'attribute' => 'dam_name',
            'value' => function (Animal $model) {
                return $model->getRelationAttributeValue('dam', 'name',$model->dam_name);
            },
            'filter' => false,
        ],
        [
            'attribute' => 'birthdate',
            'value' => function (Animal $model) {
                DateUtils::formatDate($model->birthdate, 'd-M-Y');
            },
            'filter' => false,
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{view}',
            'visibleButtons' => [
                'update' => function (Animal $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false, 'data-use-uuid' => true],
        ],
    ],
]);
?>