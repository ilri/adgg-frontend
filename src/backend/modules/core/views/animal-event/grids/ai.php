<?php

use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use common\helpers\Lang;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model AnimalEvent */
/* @var $upload_url string */

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
            'attribute' => 'event_date',
            'label' => 'AI Date',
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
        [
            'attribute' => 'breeding_aibodycondition',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_BODY_CONDITION, $model->breeding_aibodycondition);
            },
        ],
        [
            'attribute' => 'breeding_aitype',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_AI_TYPES, $model->breeding_aitype);
            },
        ],
        [
            'attribute' => 'breeding_aisemensource',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_SEMEN_SOURCE, $model->breeding_aisemensource);
            },
        ],
        [
            'attribute' => 'breeding_aisemensourceoth',
        ],
        [
            'attribute' => 'breeding_aistrawtype',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_SEMEN_TYPE, $model->breeding_aistrawtype);
            },
        ],
        [
            'attribute' => 'breeding_aistrawid',
        ],
        [
            'attribute' => 'breeding_aisirecountry',
        ],
        [
            'attribute' => 'breeding_aisirebreed',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, $model->breeding_aisirebreed);
            },
        ],
        [
            'attribute' => 'breeding_aibreedcomposition',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_BREED_COMPOSITION, $model->breeding_aibreedcomposition);
            },
        ],
        [
            'attribute' => 'breeding_aicost',
        ],
        [
            'attribute' => 'breeding_aisemenbatch',
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