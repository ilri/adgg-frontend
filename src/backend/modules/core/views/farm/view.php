<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalHerd;
use backend\modules\core\models\Farm;
use common\helpers\Lang;
use common\helpers\Url;
use yii\bootstrap\Html;
use yii\helpers\Inflector;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $model Farm */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kt-portlet kt-profile">
    <div class="kt-profile__nav">
        <ul class="nav nav-tabs nav-tabs-line my-nav" role="tablist">
            <?php if (Yii::$app->user->canUpdate()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Url::to(['farm/update', 'id' => $model->id]) ?>" role="tab">
                        <?= Lang::t('Update Farm Details') ?>
                    </a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" role="tab">
                    <?= Lang::t('Number Of Herds') ?>
                    <span class="badge badge-secondary badge-pill">
                        <?= AnimalHerd::getCount(['farm_id' => $model->id]) ?>
                    </span>
                </a>
            </li>
            <?php if ((int)AnimalHerd::getCount(['farm_id' => $model->id !== 0])): ?>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="true"
                       aria-expanded="false">
                        <?= Lang::t('Herd List') ?>
                    </a>
                    <div class="dropdown-menu">
                        <ul>
                            <?php foreach ($model->herds as $herd): ?>
                                <?php if ($model->id == $herd->farm_id): ?>
                                    <a class="button" href="<?= Url::to(['herd/view', 'id' => $herd->id]) ?>"
                                       title="Click To View Details">
                                        <h4> <?= $herd->name . '<br>' . '<div class="dropdown-divider"></div>' ?></h4>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" role="tab">
                    <?= Lang::t('Number of Animals') ?>
                    <span class="badge badge-secondary badge-pill">
                        <?= Animal::getCount(['farm_id' => $model->id]) ?>
                    </span>
                    <a>
            </li>
            <?php if ((int)Animal::getCount(['farm_id' => $model->id !== 0])): ?>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="true"
                       aria-expanded="false">
                        <?= Lang::t('Animals List') ?>
                    </a>
                    <div class="dropdown-menu">
                        <ul>
                            <?php foreach ($model->animals as $animal): ?>
                                <?php if ($model->id == $animal->farm_id): ?>
                                    <a class="button" href="<?= Url::to(['animal/view', 'id' => $animal->id]) ?>"
                                       title="Click To View Details">
                                        <h4> <?= $animal->name . '<br>' . '<div class="dropdown-divider"></div>' ?></h4>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
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
                            'attribute' => 'org_id',
                            'value' => $model->org->name,
                        ],
                        [
                            'attribute' => 'name',
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
                            'attribute' => 'farmer_name',
                        ],
                        [
                            'attribute' => 'email',
                        ],
                        [
                            'attribute' => 'field_agent_id',
                            'value' => function (Farm $model) {
                                return $model->getRelationAttributeValue('fieldAgent', 'name');
                            }
                        ],
                        [
                            'attribute' => 'field_agent_name',
                        ],
                        [
                            'attribute' => 'project',
                        ],
                        [
                            'attribute' => 'farm_type',
                        ],
                        [
                            'attribute' => 'farmer_is_hh_head',
                            'format' => 'boolean',
                        ],
                        [
                            'attribute' => 'map_address',
                        ],
                        [
                            'attribute' => 'odk_code',
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
<div class="accordion accordion-outline" id="accordion2">
    <div class="card">
        <div class="card-header" id="headingTwo">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                 aria-controls="collapseTwo">
                Location
            </div>
        </div>
        <div id="collapseTwo" class="card-body-wrapper collapse" aria-labelledby="headingTwo"
             data-parent="#accordion2" style="">
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