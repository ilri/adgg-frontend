<?php

use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use common\helpers\Lang;
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
            'attribute' => 'field_agent_id',
            'value' => function (AnimalEvent $model) {
                return $model->getRelationAttributeValue('fieldAgent', 'name');
            }
        ],
        [
            'attribute' => 'event_date',
            'label' => 'Sync Date',
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
            'attribute' => 'animalbreeding_syncnumber',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_SYNCHRONIZATION_TYPE, $model->animalbreeding_syncnumber);
            },
        ],
        [
            'attribute' => 'breeding_syncparity',
        ],
        [
            'attribute' => 'breeding_synctime',
        ],
        [
            'attribute' => 'animalbreeding_synchormonetype',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_BREEDING_HORMONES, $model->animalbreeding_synchormonetype);
            },
        ],
        [
            'attribute' => 'animalbreeding_syncsemensource',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_SEMEN_SOURCE, $model->animalbreeding_syncsemensource);
            },
        ],
        [
            'attribute' => 'breeding_syncsemensourceoth',
        ],
        [
            'attribute' => 'breeding_synccost',
        ],
        [
            'attribute' => 'animalbreeding_syncwhodid',
            'value' => function (AnimalEvent $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_BREEDING_WHO_SYNC, $model->animalbreeding_syncwhodid);
            },
        ],
        [
            'attribute' => 'breeding_syncwhodidoth',
        ],
        [
            'attribute' => 'breeding_syncwhodidothphone',
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