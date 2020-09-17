<?php

use backend\modules\core\models\AnimalBreedGroup;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use common\helpers\Utils;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $filterOptions array */
/* @var $model AnimalBreedGroup */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'showExportButton' => false,
    'columns' => [
        [
            'attribute' => 'name',
            'filter' => false,
        ],
        [
            'attribute' => 'breeds',
            'value' => function (AnimalBreedGroup $model) {
                $breeds = Choices::getColumnData('label', ['list_type_id' => ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, 'value' => $model->breeds]);
                return implode(', ', $breeds);
            },
            'filter' => false,
        ],
        [
            'attribute' => 'is_active',
            'format' => 'boolean',
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{delete}',
        ],
    ],
]);
?>