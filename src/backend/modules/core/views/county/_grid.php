<?php

use backend\modules\core\models\County;
use common\helpers\Utils;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $model County */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'columns' => [
        [
            'attribute' => 'code',
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'country',
            'filter' => \backend\modules\core\models\Country::getListData('iso2','name'),
        ],
        [
            'attribute' => 'is_active',
            'value' => function (County $model) {
                return Utils::decodeBoolean($model->is_active);
            },
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}',
        ],
    ],
]);
?>