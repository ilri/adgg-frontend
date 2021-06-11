<?php

use backend\modules\core\models\AnimalEvent;
use common\helpers\Lang;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use common\widgets\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model AnimalEvent */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate() && false, 'modal' => false],
    'toolbarButtons' => [
        Yii::$app->user->canCreate() ? '<a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to(array_merge(['upload'], Yii::$app->request->queryParams)) . '" data-pjax="0"><i class="fa fa-file-excel-o"></i> ' . Lang::t('Upload Excel/CSV') . '</a> ' : '',
    ],
    'columns' => [
        [
            'attribute' => 'event_date',
            'label' => 'Feeding Date',
            'format' => ['date', 'php:d-M-Y'],
        ],
        [
            'attribute' => 'animal_id',
            'value' => function (AnimalEvent $model) {
                return $model->animal->tag_id;
            },
        ],
        [
            'attribute' => 'animal_id',
            'label' => 'Animal Name',
            'value' => function (AnimalEvent $model) {
                return $model->animal->name;
            },
        ],
//        [
//            'attribute' => 'feed_water',
//            'enableSorting' => false,
//        ],
//        [
//            'attribute' => 'feed_given',
//            'enableSorting' => false,
//        ],
        [
            'attribute' => 'feed_given',
            'label'=>'feed_given',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_FEED_TYPE, $model->feed_given);
            },
            'enableSorting' => false,
        ],
    ],
]);
?>