<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use common\helpers\DateUtils;
use yii\bootstrap\Html;
use yii\helpers\Inflector;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $model Animal */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index', 'country_id' => $model->country_id],];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_profileHeader', ['model' => $model]) ?>


<div class="accordion accordion-outline" id="accordion1">
    <div class="card">
        <div class="card-header" id="headingOne">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false"
                 aria-controls="collapseOne">
                Animal details
            </div>
        </div>
        <div id="collapseOne" class="card-body-wrapper collapse show" aria-labelledby="headingOne"
             data-parent="#accordion1" style="">
            <div class="card-body">
                <br/>
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table detail-view table-striped'],
                    'attributes' => [
                        [
                            'attribute' => 'country_id',
                            'value' => $model->country->name,
                        ],
                        [
                            'attribute' => 'farm_id',
                            'value' => Html::a($model->farm->name . ' <i class="far fa-external-link"></i>', ['farm/view', 'id' => $model->farm_id], ['target' => '_blank']),
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'name',
                        ],
                        [
                            'attribute' => 'animal_type',
                            'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, $model->animal_type),
                        ],
                        [
                            'attribute' => 'tag_id',
                        ],
                        [
                            'attribute' => 'color',
                        ],
                        [
                            'attribute' => 'birthdate',
                            'value' => DateUtils::formatDate($model->birthdate, 'd-M-Y'),
                        ],
                        [
                            'attribute' => 'deformities',
                            'value' => Choices::getMultiSelectLabel($model->deformities, ChoiceTypes::CHOICE_TYPE_CALVE_DEFORMITY),
                        ],
                        [
                            'attribute' => 'main_breed',
                            'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, $model->main_breed)
                        ],
                        [
                            'attribute' => 'breed_composition',
                            'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_BREED_COMPOSITION, $model->breed_composition)
                        ],
                        [
                            'attribute' => 'secondary_breed',
                            'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, $model->secondary_breed),
                        ],
                        [
                            'attribute' => 'entry_type',
                            'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_ENTRY_TYPE, $model->entry_type)
                        ],
                        [
                            'attribute' => 'entry_date',
                            'value' => DateUtils::formatDate($model->entry_date, 'd-M-Y'),
                        ],
                        [
                            'attribute' => 'purchase_cost',
                            'format' => ['decimal', 2],
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
<br>
<div class="accordion accordion-outline" id="accordion2">
    <div class="card">
        <div class="card-header" id="headingTwo">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                 aria-controls="collapseTwo">
                Sire
            </div>
        </div>
        <div id="collapseTwo" class="card-body-wrapper collapse" aria-labelledby="headingTwo"
             data-parent="#accordion2" style="">
            <div class="card-body">
                <br/>
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table detail-view table-striped'],
                    'attributes' => [
                        [
                            'attribute' => 'sire_type',
                            'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_SIRE_TYPE, $model->sire_type),
                        ],
                        [
                            'attribute' => 'sire_tag_id',
                        ],
                        [
                            'attribute' => 'sire_name',
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
<br>
<div class="accordion accordion-outline" id="accordion3">
    <div class="card">
        <div class="card-header" id="headingThree">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                 aria-controls="collapseThree">
                Dam
            </div>
        </div>
        <div id="collapseThree" class="card-body-wrapper collapse" aria-labelledby="headingThree"
             data-parent="#accordion3" style="">
            <div class="card-body">
                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table detail-view table-striped'],
                            'attributes' => [
                                [
                                    'attribute' => 'dam_tag_id',
                                ],
                                [
                                    'attribute' => 'dam_name',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="accordion accordion-outline" id="accordion4">
    <div class="card">
        <div class="card-header" id="headingThree">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
                 aria-controls="collapseFour">
                Location
            </div>
        </div>
        <div id="collapseFour" class="card-body-wrapper collapse" aria-labelledby="headingFour"
             data-parent="#accordion4" style="">
            <div class="card-body">
                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <?= \common\widgets\gmap\SingleViewWidget::widget([
                            'latitude' => $model->latitude,
                            'longitude' => $model->longitude,
                            'showDefaultMap' => false,
                            'mapWrapperHtmlOptions' => ['style' => 'height:400px;'],
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>