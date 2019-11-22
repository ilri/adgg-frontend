<?php

use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use common\helpers\Lang;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this \yii\web\View */
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
        [
            'attribute' => 'event_date',
            'label' => 'Milk Date',
            'format' => ['date', 'php:d-M-Y'],
        ],
        [
            'attribute' => 'milkmor',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'milkeve',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'milkday',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'milkfat',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'milkprot',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'milklact',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'milksmc',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'milkurea',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'milkurea',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'milk_qty_is_tested',
            'format' => 'boolean',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'milk_sample_type',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_MILK_SAMPLE_TYPE, $model->milk_sample_type);
            },
            'enableSorting' => false,
        ],
    ],
]);
?>