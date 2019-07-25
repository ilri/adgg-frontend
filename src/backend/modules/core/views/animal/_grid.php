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
    'filterModel' => $model,
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
            'attribute' => 'sire_id',
            'value' => function (Animal $model) {
                return $model->getRelationAttributeValue('sire', 'name');
            },
            'filter' => false,
        ],
        [
            'attribute' => 'dam_id',
            'value' => function (Animal $model) {
                return $model->getRelationAttributeValue('dam', 'name');
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
            'template' => '{update}{view}',
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