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
        Yii::$app->user->canCreate() ? '<a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to([$upload_url]) . '" data-pjax="0"><i class="fa fa-file-excel-o"></i> ' . Lang::t('Upload Excel/CSV') . '</a> ' : '',
    ],
    'columns' => [
        [
            'attribute' => 'event_date',
            'label' => 'Examination Date',
            'format' => ['date', 'php:d-M-Y'],
        ],
        [
            'attribute' => 'breeding_pdexamtime',
            'enableSorting' => false,
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
        [
            'attribute' => 'animalbreeding_pdserviceknown',
            'format' => 'boolean',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'breeding_pdservicedate',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'animalbreeding_pdresult',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PD_RESULT, $model->animalbreeding_pdresult);
            },
            'enableSorting' => false,
        ],
        [
            'attribute' => 'breeding_pdstage',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PD_STAGE, $model->breeding_pdstage);
            },
            'enableSorting' => false,
        ],
        [
            'attribute' => 'breeding_pdbodyscore',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_BODY_CONDITION, $model->breeding_pdbodyscore);
            },
            'enableSorting' => false,
        ],
        [
            'attribute' => 'breeding_pdmethod',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PD_METHOD, $model->breeding_pdmethod);
            },
            'enableSorting' => false,
        ],
        [
            'attribute' => 'breeding_pdcost',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'field_agent_id',
            'value' => function (AnimalEvent $model) {
                return $model->getRelationAttributeValue('fieldAgent', 'name');
            },
        ],
    ],
]);
?>