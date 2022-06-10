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
            'attribute' => 'field_agent_id',
            'value' => function (AnimalEvent $model) {
                return $model->getRelationAttributeValue('fieldAgent', 'name');
            }
        ],
        [
            'attribute' => 'animal_id',
            'value' => function (AnimalEvent $model) {
                return $model->animal->tag_id;
            },
        ],
//        [
//            'attribute' => 'animal_id',
//            'label' => 'Animal Name',
//            'value' => function (AnimalEvent $model) {
//                return $model->animal->name;
//            },
//
//        ],
        [
            'attribute' => 'event_date',
            'label' => 'Collection Date',
            'format' => ['date', 'php:d-M-Y'],
        ],
        [
            'attribute' => 'calvtype',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_CALVING_TYPE, $model->calvtype);
            },
            'enableSorting' => false,
        ],
        [
            'attribute' => 'easecalv',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_EASE_OF_CALVING, $model->easecalv);
            },
            'enableSorting' => false,

        ],
        [
            'attribute' => 'birthtyp',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_BIRTH_TYPE, $model->birthtyp);
            },
            'enableSorting' => false,
            'visible' => false,
        ],
        [
            'attribute' => 'calfsex',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_GENDER, $model->calfsex);
            },
            'enableSorting' => false,

        ],
        [
            'attribute' => 'calfsiretype',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_SIRE_TYPE, $model->calfsiretype);
            },
            'enableSorting' => false,
        ],
        [
            'attribute' => 'aiprov',
            'value' => function (AnimalEvent $model) {
                if ($model->aiprov == Choices::CHOICES_OTHERS_SPECIFY) {
                    return $model->aiprov_other;
                } else {
                    return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_AI_PROVIDER, $model->aiprov);
                }
            },
            'enableSorting' => false,
        ],
        [
            'attribute' => 'intuse',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_CALVE_USE, $model->intuse);
            },
            'enableSorting' => false,
        ],
        [
            'attribute' => 'calvdatedead',
            'visible' => false,
            'enableSorting' => false,
        ],
        [
            'attribute' => 'whydead',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_WHY_DEAD, $model->whydead);
            },
            'visible' => false,
            'enableSorting' => false,
        ],
        [
            'attribute' => 'calfname',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'tag_id',
        ],
        [
            'attribute' => 'calfcolor',
            'enableSorting' => false,
        ],
    ],
]);
?>