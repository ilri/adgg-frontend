<?php

use common\widgets\grid\GridView;
use backend\modules\reports\models\AdhocReport;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model AdhocReport */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => false, 'modal' => false],
    'toolbarButtons' => [
        //Yii::$app->user->canCreate() ? '<a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to(['upload', 'org_id' => $model->org_id, 'level_id' => UserLevels::LEVEL_DISTRICT]) . '" data-pjax="0"><i class="fa fa-file-excel-o"></i> ' . Lang::t('Upload Enumerators/AITech') . '</a> ' : '',
    ],
    'rowOptions' => function (AdhocReport $model) {
        return ["class" => "linkable", "data-href" => Url::to(['view', "id" => $model->id])];
    },
    'columns' => [
        [
            'attribute' => 'name',
            'filter' => true,
        ],
        [
            'attribute' => 'raw_sql',
            'filter' => false,
            'enableSorting' => false,
        ],
        [
            'attribute' => 'report_file',
            'filter' => false,
            'value' => function (AdhocReport $model) {
                return Html::a($model->report_file, ['download-file', 'id' => $model->id], ['data-pjax' => 0]);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'status',
            'filter' => AdhocReport::statusOptions(),
            'value' => function (AdhocReport $model) {
                return AdhocReport::decodeStatus($model->status);
            },
        ],
        [
            'attribute' => 'status_remarks',
            'filter' => false,
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{view}{update}{reload}',
            'visibleButtons' => [
                'update' => false,
                'reload' => function(AdhocReport $model){
                    return ($model->status == AdhocReport::STATUS_COMPLETED || $model->status == AdhocReport::STATUS_ERROR);
                },
            ],
            'buttons'=>[
                'reload' => function ($url, AdhocReport $model) {
                    $url = Url::to(['requeue', 'id' => $model->id]);
                    return Html::a('<i class="fas fa-redo"></i>', $url, ['title' => 'Re-add back to Queue', 'data-pjax' => 0, 'class' => 'grid-update', 'data-grid' => $model->getPjaxWidgetId(), 'data-href' => $url]);
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false],
        ],
    ],
]);
?>