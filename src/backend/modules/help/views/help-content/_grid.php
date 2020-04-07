<?php

use backend\modules\help\models\HelpContent;
use backend\modules\help\models\HelpModules;
use common\helpers\Lang;
use common\widgets\grid\GridView;
use common\helpers\Url;

?>
<?php
$case = Yii::$app->request->get('forAndroid');
if ($case == true) {
    $webUrl = ['read', 'forAndroid' => true];
    $pdfUrl = ['read', 'forAndroid' => true, 'format' => 'pdf'];
} else {
    $webUrl = ['read', 'forAndroid' => false];
    $pdfUrl = ['read', 'forAndroid' => false, 'format' => 'pdf'];
}
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'refreshUrl' => array_merge(['index'], Yii::$app->controller->actionParams),
    'toolbarButtons' => [
        Yii::$app->user->canView() ? '<a target="_blank" class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to($webUrl) . '" data-pjax="0"><i class="far fa-browser"></i> ' . Lang::t('Read on Web') . '</a> ' : '',
        Yii::$app->user->canView() ? '<a target="_blank" class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to($pdfUrl) . '" data-pjax="0"><i class="far fa-file-pdf"></i> ' . Lang::t('Read as PDF') . '</a> ' : '',
    ],
    'showExportButton' => false,
    'rowOptions' => function (HelpContent $model) {
        return ["class" => "linkable", "data-href" => Url::to(['update', "id" => $model->id])];
    },
    'columns' => [
        'name',
        [
            'attribute' => 'module_id',
            'value' => function (HelpContent $model) {
                return HelpModules::getFieldByPk($model->module_id, 'name');
            },
            'filter' => HelpModules::getListData()
        ],
        [
            'class' => \common\widgets\grid\ActionColumn::class,
            'template' => '{delete}{update}',
            'buttons' => [],
            'updateOptions' => [
                'data-pjax' => 0,
                'class' => '',
                'modal' => false,
            ],
        ],
    ]
]);
?>
