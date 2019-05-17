<?php
use common\widgets\grid\GridView;

?>

<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'refreshUrl'=> array_merge(['index'],Yii::$app->controller->actionParams),
    'toolbarButtons' => [],
    'showExportButton' => false,
    'columns' => [
        'name',
        [
            'attribute' => 'resource_name',
            'filter' => \backend\modules\auth\models\Resources::getListData('id', 'name', '--All--')
        ],
        [
            'class' => \common\widgets\grid\ActionColumn::class,
            'template' => '{update}',
            'buttons' => [],
        ],
    ]
]);
?>
