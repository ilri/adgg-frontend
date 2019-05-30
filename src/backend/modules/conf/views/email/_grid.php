<?php

use backend\modules\conf\models\EmailTemplate;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model EmailTemplate */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'striped' => true,
    'bordered' => true,
    'rowOptions' => function (EmailTemplate $model) {
        return ["class" => "linkable", "data-href" => Url::to(['update', "id" => $model->id])];
    },
    'columns' => [
        [
            'attribute' => 'id',
            'visible' => \backend\modules\auth\Session::isDev(),
        ],
        [
            'attribute' => 'name',
            'filter' => true,
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{delete}',
            'deleteOptions' => [
            ],
            'updateOptions' => [
                'data-pjax' => 0,
                'modal' => false,
            ],
            'visibleButtons' => [
                'update' => function (EmailTemplate $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
        ],
    ],
]);
?>