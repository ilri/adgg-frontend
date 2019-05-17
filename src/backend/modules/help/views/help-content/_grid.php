<?php

use backend\modules\help\models\HelpContent;
use backend\modules\help\models\HelpModules;
use common\widgets\grid\GridView;
use common\helpers\Url;

?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'refreshUrl' => array_merge(['index'], Yii::$app->controller->actionParams),
    'toolbarButtons' => [],
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
                'class'=>'',
            ],
        ],
    ]
]);
?>
