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
            'attribute' => 'farm_id',
            'value' => function (Animal $model) {
                return $model->getRelationAttributeValue('fieldAgent', 'name');
            },
            'filter' => false,
        ],
        [
            'attribute' => 'event_date',
            'label' => 'Vaccination Date',
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
//            'attribute' => 'injury_type',
//            'value' => function (AnimalEvent $model) {
//                return Choices::getMultiSelectLabel($model->injury_type,ChoiceTypes::CHOICE_TYPE_ANIMAL_INJURIES);
//            },
//            'enableSorting' => false,
//        ],
        [
            'attribute' => 'injury_type_other',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'injury_service_provider',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'injury_service_provider_other',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'injury_drug_cost',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'injury_service_cost',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'injury_cow_status',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'injury_cow_status_other',
            'enableSorting' => false,
        ],
    ],
]);
?>