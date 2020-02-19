<?php

use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $country Country */
/* @var $dataProvider CountriesDashboardStats */
?>
<?php
$gridData = CountriesDashboardStats::getGetAnimalsMilkingRecords();
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'title' => 'Cows Milking Records',
    //'filterModel' => $dataProvider,
    'filterRowOptions' => ['class' => 'filters'],
    'createButton' => ['visible' => false],
    'columns' => [
        [
            'attribute' => 'tag_id',
            'label' => 'Animal Tag ID',
        ],
        [
            'attribute' => 'name',
            'label' => 'Animal Name',
        ],
        [
            'attribute' => 'main_breed',
            'label' => 'Main Breed',
            'value' => function ($gridData) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, $gridData['main_breed']);
            },
        ],
        [
            'label' => 'No of Milking',
            'attribute' => 'milkRecordsCount',
        ],
        [
            'label' => 'Average Milk Yield',
            'attribute' => 'average',
            'format' => ['decimal', 2]
        ],
    ],
]);

?>