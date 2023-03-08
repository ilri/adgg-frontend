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
            'label' => 'Hoof Health Event Date',
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
            'attribute' => 'hoof_dd',
            'label' => 'Digital dermatitis',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'hoof_ih',
            'label' => 'Interdigital hyperplasia',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'hoof_ip',
            'label' => 'Interdigital Phlegmon',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'hoof_sc',
            'label' => 'Scissor claws',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'hoof_hfh',
            'label' => 'Horizontal horn fissure',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'hoof_vfh',
            'label' => 'Vertical horn fissure',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'hoof_sw',
            'label' => 'Swelling of coronet and/or bulb',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'hoof_hhe',
            'label' => 'Heel horn erosion',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'Other hoof problems',
            'enableSorting' => false,
        ],
    ],
]);
?>