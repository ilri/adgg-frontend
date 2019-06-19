<?php

use backend\modules\core\models\TableAttributesGroup;
use common\helpers\Lang;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $filterOptions array */
/* @var $model TableAttributesGroup */
?>
<?= GridView::widget([
    'title' => Lang::t('Table Attributes Groups'),
    'searchModel' => $model,
    'createButton' => [
        'visible' => Yii::$app->user->canCreate(),
        'modal' => true,
        'url' => Url::to(['table-attributes-group/create', 'table_id' => $model->table_id]),
        'label' => '<i class="fa fa-plus-circle"></i> ' . Lang::t('Add Group'),
    ],
    'showExportButton' => false,
    'columns' => [
        [
            'attribute' => 'name',
            'filter' => false,
        ],
        [
            'attribute' => 'description',
            'filter' => false,
            'visible' => false,
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{delete}',
            'controller' => 'table-attributes-group',
        ],
    ],
]);
?>