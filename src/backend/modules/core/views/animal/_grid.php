<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\widgets\grid\GridView;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model Animal */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => false, 'modal' => false],
    'rowOptions' => function (Animal $model) {
        return ["class" => "linkable", "data-href" => Url::to(['view', "id" => $model->uuid, 'animal_type' => $model->animal_type])];
    },
    'toolbarButtons' => [
        Yii::$app->user->canCreate() ? '<a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to(array_merge(['upload'], Yii::$app->request->queryParams)) . '" data-pjax="0"><i class="fa fa-file-excel-o"></i> ' . Lang::t('Upload Excel/CSV') . '</a> ' : '',
    ],
    'columns' => [
        [
            'attribute' => 'tag_id',
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'farm_id',
            'value' => function (Animal $model) {
                return $model->getRelationAttributeValue('farm', 'name');
            },
            'filter' => false,
        ],
        [
            'attribute' => 'animal_type',
            'value' => function (Animal $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, $model->animal_type);
            }
        ],
        [
            'attribute' => 'color',
            'value' => function (Animal $model) {
                return !empty($model->color) ? Choices::getMultiSelectLabel($model->color, ChoiceTypes::CHOICE_TYPE_ANIMAL_COLORS) : $model->color_other;
            }

        ],
        [
            'attribute' => 'main_breed',
            'value' => function (Animal $model) {
                return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, $model->main_breed);
            }
        ],
        [
            'attribute' => 'sire_tag_id',
        ],
        [
            'label' => 'Sire Name',
            'value' => function (Animal $model) {
                return $model->getRelationAttributeValue('sire', 'name');
            },
            'filter' => false,
        ],
        [
            'attribute' => 'dam_tag_id',
        ],
        [
            'label' => 'Dam Name',
            'value' => function (Animal $model) {
                return $model->getRelationAttributeValue('dam', 'name');
            },
            'filter' => false,
        ],
        [
            'attribute' => 'birthdate',
            'value' => function (Animal $model) {
                DateUtils::formatDate($model->birthdate, 'd-M-Y');
            },
            'filter' => false,
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{view}',
            'visibleButtons' => [
                'view' => function (Animal $model) {
                    return Yii::$app->user->canView();
                },
                'update' => function (Animal $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false, 'data-use-uuid' => true],
        ],
    ],
]);
?>