<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Farm;
use common\widgets\gmap\SingleViewWidget;
use yii\bootstrap\Html;
use yii\helpers\Inflector;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $model Farm */
/* @var $animal Animal */


/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index', 'country_id' => $model->country_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_profileHeader', ['model' => $model]) ?>
<div class="accordion accordion-outline" id="accordion1">
    <div class="card">
        <div class="card-header" id="headingOne">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false"
                 aria-controls="collapseOne">
                Farm Details
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
                            'attribute' => 'name',
                        ],
                        [
                            'attribute' => 'farm_type',
                        ],
                        [
                            'attribute' => 'country_id',
                            'value' => $model->country->name,
                        ],
                        [
                            'attribute' => 'region_id',
                            'value' => function (Farm $model) {
                                return $model->getRelationAttributeValue('region', 'name');
                            },
                            'hidden' => false,
                        ],
                        [
                            'attribute' => 'district_id',
                            'value' => function (Farm $model) {
                                return $model->getRelationAttributeValue('district', 'name');
                            },
                            'hidden' => false,
                        ],
                        [
                            'attribute' => 'ward_id',
                            'value' => function (Farm $model) {
                                return $model->getRelationAttributeValue('ward', 'name');
                            },
                            'hidden' => false,
                        ],
                        [
                            'attribute' => 'village_id',
                            'value' => function (Farm $model) {
                                return $model->getRelationAttributeValue('village', 'name');
                            },
                            'hidden' => false,
                        ],
                        [
                            'attribute' => 'reg_date',
                        ],
                        [
                            'attribute' => 'project',
                        ],
                        [
                            'attribute' => 'map_address',
                        ],
                        [
                            'attribute' => 'is_active',
                            'format' => 'boolean',
                        ],
                    ],
                ])
                ?>
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
                Farmer Details
            </div>
        </div>
        <div id="collapseTwo" class="card-body-wrapper collapse" aria-labelledby="headingTwo"
             data-parent="#accordion2" style="">
            <div class="card-body">
                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table detail-view table-striped'],
                            'attributes' => [
                                [
                                    'attribute' => 'farmer_name',
                                ],
                                [
                                    'attribute' => 'farmer_is_hh_head',
                                    'format' => 'boolean',
                                ],
                                [
                                    'attribute' => 'email',
                                ],
                                [
                                    'attribute' => 'phone',
                                ],
                                [
                                    'attribute' => 'odk_code',
                                ],
                                [
                                    'attribute' => 'farmer_age',
                                ],
                                [
                                    'attribute' => 'farmer_age_range',
                                    'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PERSON_AGE_GROUP, $model->farmer_age_range),
                                ],
                                [
                                    'attribute' => 'farmer_relationship_to_hhh',
                                    'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PERSON_RELATIONSHIP, $model->farmer_relationship_to_hhh),
                                ],
                                [
                                    'attribute' => 'farmer_relationship_to_hhh_other',
                                    'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PERSON_RELATIONSHIP, $model->farmer_relationship_to_hhh_other),
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
<div class="accordion accordion-outline" id="accordion3">
    <div class="card">
        <div class="card-header" id="headingThree">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                 aria-controls="collapseThree">
                Field Agent
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
                                    'attribute' => 'field_agent_name',
                                    'value' => function (Farm $model) {
                                        return $model->getRelationAttributeValue('fieldAgent', 'name');
                                    }
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
        <div class="card-header" id="headingFour">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
                 aria-controls="collapseFour">
                Household Details
            </div>
        </div>
        <div id="collapseFour" class="card-body-wrapper collapse" aria-labelledby="headingFour"
             data-parent="#accordion4" style="">
            <div class="card-body">
                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table detail-view table-striped'],
                            'attributes' => [
                                [
                                    'attribute' => 'hhh_age',
                                ],
                                [
                                    'attribute' => 'hhh_age_range',
                                    'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PERSON_AGE_GROUP, $model->hhh_age_range),

                                ],
                                [
                                    'attribute' => 'hhh_gender',
                                    'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_GENDER, $model->hhh_gender),

                                ],
                                [
                                    'attribute' => 'hhh_mobile',
                                ],
                                [
                                    'attribute' => 'hhh_name',
                                ],

                                [
                                    'attribute' => 'hhproblems_other',
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
<div class="accordion accordion-outline" id="accordion5">
    <div class="card">
        <div class="card-header" id="headingFive">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false"
                 aria-controls="collapseFive">
                Cattle
            </div>
        </div>
        <div id="collapseFive" class="card-body-wrapper collapse" aria-labelledby="headingFive"
             data-parent="#accordion5" style="">
            <div class="card-body">
                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table detail-view table-striped'],
                            'attributes' => [
                                [
                                    'attribute' => 'total_cattle_owned',
                                ],
                                [
                                    'attribute' => 'total_cattle_owned_by_female',
                                ],
                                [
                                    'attribute' => 'total_cattle_owned_by_male',
                                ],
                                [
                                    'attribute' => 'total_cattle_owned_joint',
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
<div class="accordion accordion-outline" id="accordion6">
    <div class="card">
        <div class="card-header" id="headingSix">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false"
                 aria-controls="collapseSix">
                Statistics
            </div>
        </div>
        <div id="collapseSix" class="card-body-wrapper collapse" aria-labelledby="headingSix"
             data-parent="#accordion6" style="">
            <div class="card-body">
                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table detail-view table-striped'],
                            'attributes' => [
                                [
                                    'attribute' => 'nfemale15to64',
                                ],
                                [
                                    'attribute' => 'nfemale6to14',
                                ],
                                [
                                    'attribute' => 'nfemaleo64',
                                ],
                                [
                                    'attribute' => 'nmale0to5',
                                ],
                                [
                                    'attribute' => 'nmale15to64',
                                ],

                                [
                                    'attribute' => 'nmale6to14',
                                ],
                                [
                                    'attribute' => 'nmaleo64',
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

<div class="accordion accordion-outline" id="accordion7">
    <div class="card">
        <div class="card-header" id="headingSeven">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false"
                 aria-controls="collapseSeven">
                Location
            </div>
        </div>
        <div id="collapseSeven" class="card-body-wrapper collapse" aria-labelledby="headingSeven"
             data-parent="#accordion7" style="">
            <div class="card-body">
                <br/>
                <div class="row">
                    <div class="col-lg-12">
                        <?= SingleViewWidget::widget([
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
<br>
