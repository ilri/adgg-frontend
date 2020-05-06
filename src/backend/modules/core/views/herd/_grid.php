<?php

use backend\modules\core\models\AnimalHerd;
use common\helpers\Lang;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model AnimalHerd */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate() && false, 'modal' => false],
    'toolbarButtons' => [
        Yii::$app->user->canCreate() ? '<a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to(array_merge(['upload'], Yii::$app->request->queryParams)) . '" data-pjax="0"><i class="fa fa-file-excel-o"></i> ' . Lang::t('Upload Excel/CSV') . '</a> ' : '',
    ],
    'rowOptions' => function (AnimalHerd $model) {
        //return ["class" => "linkable", "data-href" => Url::to(['view', "id" => $model->uuid])];
    },
    'columns' => [
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'reg_date',
            'format' => ['date', 'php:d-M-Y'],
            'visible' => false,
        ],
        [
            'attribute' => 'farmerName',
            'value' => function (AnimalHerd $model) {
                return $model->getRelationAttributeValue('farm', 'farmer_name');
            },
        ],
        [
            'attribute' => 'farmerPhone',
            'value' => function (AnimalHerd $model) {
                return $model->getRelationAttributeValue('farm', 'phone');
            },
        ],
        [
            'attribute' => 'farmerEmail',
            'value' => function (AnimalHerd $model) {
                return $model->getRelationAttributeValue('farm', 'email');
            },
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{view}',
            'visibleButtons' => [
                'view' => function (AnimalHerd $model) {
                    return Yii::$app->user->canView();
                },
                'update' => function (AnimalHerd $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false, 'data-use-uuid' => false],
        ],
    ],
]);
?>