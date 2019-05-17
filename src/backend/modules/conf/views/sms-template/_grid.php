<?php

use backend\modules\conf\models\SmsTemplate;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $model SmsTemplate */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'showExportButton' => false,
    'columns' => [
        [
            'attribute' => 'code',
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'template',
            'value' => function (SmsTemplate $model) {
                return \Illuminate\Support\Str::limit($model->template);
            }
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}',
        ],
    ],
]);
?>