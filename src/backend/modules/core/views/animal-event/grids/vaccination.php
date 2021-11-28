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
            'attribute' => 'field_agent_id',
            'value' => function (AnimalEvent $model) {
                return $model->getRelationAttributeValue('fieldAgent', 'name');
            }
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
//            'attribute' => 'vacc_vaccine_type',
//            'label'=>'Vaccine Type',
//            'value' => function (AnimalEvent $model) {
//                return Choices::getMultiSelectLabel($model->vacc_vaccine_type,ChoiceTypes::CHOICE_TYPE_VACCINE_TYPE);
//            },
//            'enableSorting' => true,
//        ],
        [
            'attribute' => 'vacc_vaccine_provider',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'vacc_vaccine_provider_other',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'vacc_vaccine_drug_cost',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'vacc_vaccine_service_cost',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'diseases_screened',
            'label'=>'Which ones do you screen for?',
            'value' => function (AnimalEvent $model) {
                return Choices::getMultiSelectLabel($model->diseases_screened,ChoiceTypes::CHOICE_TYPE_DISEASES_SCREENED);
            },
            'enableSorting' => true,
        ],
        [
            'attribute' => 'diseases_acaricides_type',
            'label'=>'Which acaricides do you use?',
            'value' => function (AnimalEvent $model) {
                return $model->diseases_acaricides_type;
            },
            'enableSorting' => true,
        ],
    ],
]);
?>