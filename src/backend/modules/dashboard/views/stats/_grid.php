<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $model AnimalEvent */
?>

<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'title' => 'Test Day Milk',
    'createButton' => ['visible' => false],
    'columns' => [
        [
            'attribute' => 'farm_id',
            'label' => 'Farm',
            'value' => function (Animal $model) {
                return $model->getRelationAttributeValue('farm', 'name');
            }
        ],
        [
            'label' => 'No.Of Animals',
            'attribute' => 'id',
        ],
        [
            'label' => 'No of Milk Records',
            'attribute' => 'id',
        ],
        [
            'label' => 'Average Milk Yield',
            'attribute' => 'id',
        ],
    ],
]);
//$n=new GridView;
//$n->title;


?>