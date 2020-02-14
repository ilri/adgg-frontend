<?php

use backend\modules\core\models\MilkingReport;
use common\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider MilkingReport */
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'title' => 'Test Day Milk',
    'createButton' => ['visible' => false],
    'columns' => [
        [
            'attribute' => 'farmer_name',
            'label' => 'Farmer Name',
        ],
        [
            'label' => 'No.Of Animals',
            'attribute' => 'animalCount',
        ],
        [
            'label' => 'No of Milk Records',
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