<?php

use backend\modules\core\models\AnimalEvent;
use common\helpers\Lang;
use yii\bootstrap\Html;
use yii\helpers\Inflector;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $model AnimalEvent */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = Html::encode($model::decodeEventType($model->event_type));
$this->params['breadcrumbs'][] = ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <h4 class="card-header" role="tab" id="headingOne" style="padding: 10px;">
        <?= Lang::t('Event Details') ?>
    </h4>
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table detail-view table-striped'],
        'attributes' => [
            [
                'attribute' => 'event_type',
                'value' => function (AnimalEvent $model) {
                    return $model::decodeEventType($model->event_type);
                }
            ],
            [
                'attribute' => 'org_id',
                'value' => $model->org->name,
            ],
            [
                'attribute' => 'region_id',
                'value' => function (AnimalEvent $model) {
                    return $model->getRelationAttributeValue('region', 'name');
                },
                'hidden' => false,
            ],
            [
                'attribute' => 'district_id',
                'value' => function (AnimalEvent $model) {
                    return $model->getRelationAttributeValue('district', 'name');
                },
                'hidden' => false,
            ],
            [
                'attribute' => 'ward_id',
                'value' => function (AnimalEvent $model) {
                    return $model->getRelationAttributeValue('ward', 'name');
                },
                'hidden' => false,
            ],
            [
                'attribute' => 'village_id',
                'value' => function (AnimalEvent $model) {
                    return $model->getRelationAttributeValue('village', 'name');
                },
                'hidden' => false,
            ],
            [
                'attribute' => 'event_date',
            ],
            [
                'attribute' => 'latitude',
            ],
            [
                'attribute' => 'longitude',
            ],
            [
                'attribute' => 'map_address',
            ],
            [
                'attribute' => 'latlng',
            ],
        ],
    ])
    ?>
</div>