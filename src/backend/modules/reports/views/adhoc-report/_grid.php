<?php

use common\helpers\Lang;
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
        //Yii::$app->user->canCreate() ? '<a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to(['upload', 'country_id' => $model->country_id, 'level_id' => UserLevels::LEVEL_DISTRICT]) . '" data-pjax="0"><i class="fa fa-file-excel-o"></i> ' . Lang::t('Upload Enumerators/AITech') . '</a> ' : '',
    ],
    'rowOptions' => function (AdhocReport $model) {
        return ["class" => "_linkable " . AdhocReport::decodeStatus($model->status), "data-href" => Url::to(['view', "id" => $model->id])];
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
            'visible' => false,
        ],
        [
            'attribute' => 'report_file',
            'filter' => false,
            'value' => function (AdhocReport $model) {
                return Html::a($model->report_file, ['/reports/adhoc-report/download-file', 'id' => $model->id], ['data-pjax' => 0]);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'status',
            'filter' => AdhocReport::statusOptions(),
            'format' => 'html',
            'value' => function (AdhocReport $model) {
                if($model->status == AdhocReport::STATUS_PROCESSING) {
                    return Html::label(Lang::t('Processing ') . '  <i class="fas fa-spinner fa-spin fa-2x" style="color:#38997a;"></i>');
                }
                else {
                    return AdhocReport::decodeStatus($model->status);
                }
            },
        ],
        [
            'attribute' => 'status_remarks',
            'filter' => false,
        ],
        [
            'attribute' => 'error_message',
            'label' => 'Errors',
            'filter' => false,
            'format' => 'raw',
            'value' => function(AdhocReport $model){
                if($model->status == AdhocReport::STATUS_ERROR){
                    $url = Url::to(['/reports/adhoc-report/errors', 'id' => $model->id]);
                    return Html::a('View Errors', '#', ['title' => 'View Errors', 'data-toggle' => 'modal', 'data-href' => $url]);
                }
                return '';

            },
            'visible' => \backend\modules\auth\Session::isDev(),
        ],
        [
            'attribute' => 'created_by',
            'label' => 'Extracted By',
            'value' => function (AdhocReport $model) {
                return $model->getRelationAttributeValue('extractedBy', 'name');
            }
        ],
        [
            'label' => 'Date',
            'filter' => false,
            'format' => 'html',
            'value' => function (AdhocReport $model) {
                return \common\helpers\DateUtils::formatToLocalDate($model->created_at);
            },
        ],
        [
            'label' => 'Download',
            'filter' => false,
            'format' => 'raw',
            'value' => function (AdhocReport $model) {
                return Html::a(Lang::t('Download Report') . ' <i class="fas fa-download"></i>', ['/reports/adhoc-report/download-file', 'id' => $model->id], ['data-pjax' => 0]);
            },
            'visible' => false,
        ],
        [
            'label' => 'Rebuild',
            'filter' => false,
            'format' => 'raw',
            'value' => function (AdhocReport $model) {
                if($model->is_standard || \common\helpers\Str::contains($model->name, ['Milk_Data_', 'Pedigree_', 'Calf_Data_', 'Pedigree_File_', 'TestDayMilk_Data_'])) {
                    return Html::a(Lang::t('Rebuild Report') . ' <i class="fas fa-paint-roller"></i>', ['/reports/default/view','type' => $model->type, 'country_id' => $model->country_id ?? '', 'rebuild_id' => $model->id], ['data-pjax' => 0, 'target' => '_blank']);;
                }
                else {
                    return Html::a(Lang::t('Rebuild Report') . ' <i class="fas fa-paint-roller"></i>', ['/reports/builder/index', 'country_id' => $model->country_id ?? '', 'rebuild_id' => $model->id], ['data-pjax' => 0, 'target' => '_blank']);
                }
            },
            'visible' => true,
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{view}{update}{reload}{delete}',
            'visibleButtons' => [
                'update' => false,
                'reload' => function (AdhocReport $model) {
                    return ($model->status == AdhocReport::STATUS_COMPLETED || $model->status == AdhocReport::STATUS_ERROR);
                },
                'delete' => function (AdhocReport $model) {
                    return ($model->status != AdhocReport::STATUS_QUEUED);
                },
            ],
            'buttons' => [
                'view' => function ($url, AdhocReport $model) {
                    $url = Url::to(['/reports/adhoc-report/view', 'id' => $model->id]);
                    return Html::a('<i class="fas fa-eye"></i>', $url, ['title' => 'View Report', 'data-pjax' => 0, 'class' => '_grid-update', 'data-grid' => $model->getPjaxWidgetId(), 'data-href' => $url]);
                },
                'reload' => function ($url, AdhocReport $model) {
                    $url = Url::to(['/reports/adhoc-report/requeue', 'id' => $model->id]);
                    return Html::a('<i class="fas fa-redo"></i>', '#', ['title' => 'Re-generate Report', 'data-pjax' => 0, 'data-toggle' => 'modal', 'class' => '_grid-update', 'data-grid' => $model->getPjaxWidgetId(), 'data-href' => $url]);
                },
                'delete' => function($url, AdhocReport $model) {
                    $url = Url::to(['/reports/adhoc-report/delete', 'id' => $model->id]);
                    return Html::a('<i class="text-muted fas fa-trash"></i>', 'javascript:void(0);', [
                        'title' => 'Delete',
                        'data-confirm-message' => 'You are about to permanently delete the item/s. Are you sure?',
                        'data-pjax' => 0,
                        'class' => 'grid-update',
                        'data-grid' => $model->getPjaxWidgetId(),
                        'data-href' => $url
                    ]);
                },
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false],
        ],
    ],
]);
?>