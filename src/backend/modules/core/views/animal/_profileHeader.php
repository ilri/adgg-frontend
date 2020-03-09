<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use common\helpers\DateUtils;
use common\helpers\Lang;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model Animal */
?>

    <div class="kt-portlet kt-profile">
        <div class="kt-profile__content">
            <div class="row">
                <div class="col-md-12 col-lg-5 col-xl-3">
                    <div class="kt-profile__main">
                        <div class="kt-profile__main-pic">
                            <i class="far fa-cow fa-4x"></i>
                        </div>
                        <div class="kt-profile__main-info">
                            <div class="kt-profile__main-info-name"><?= Html::encode($model->name) ?></div>
                            <div class="kt-profile__main-info-position"><?= Html::encode($model->tag_id) ?></div>
                            <div class="kt-profile__main-info-position"><?= Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, $model->animal_type) ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 col-xl-3">
                    <div class="kt-profile__main">
                        <div class="kt-profile__main-pic">
                            <i class="far fa-tractor fa-3x"></i>
                        </div>
                        <div class="kt-profile__main-info">
                            <div class="kt-profile__main-info-name"><?= Html::encode($model->farm->name) ?></div>
                            <div class="kt-profile__main-info-position"><?= Html::encode($model->farm->farm_type) ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 col-xl-3">
                    <div class="kt-profile__contact">
                        <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-twitter">
                            <i class="fas fa-user"></i> Farmer:</span>
                            <span class="kt-profile__contact-item-text"><?= Html::encode($model->farm->farmer_name) ?></span>
                        </a>
                        <?php if (!empty($model->farm->phone)): ?>
                            <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-whatsup">
                            <i class="fas fa-phone"></i> Phone:</span>
                                <span class="kt-profile__contact-item-text"><?= Html::encode($model->farm->phone) ?></span>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($model->farm->email)): ?>
                            <a href="mailto:<?= $model->farm->email ?>" target="_blank"
                               class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon">
                            <i class="fas fa-envelope"></i> Email:</span>
                                <span class="kt-profile__contact-item-text"><?= Html::encode($model->farm->email) ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (($model->animal_type == Animal::ANIMAL_TYPE_COW)): ?>
                    <div class="col-md-12 col-lg-3 col-xl-3">
                        <div class="kt-profile__stats">
                            <div class="kt-profile__stats-item">
                                <div class="kt-profile__stats-item-label">Last Calving Date</div>
                                <div class="kt-profile__stats-item-chart">
                                    <span><?= AnimalEvent::getEventLastDate($model->id, AnimalEvent::EVENT_TYPE_CALVING) ?></span>
                                    <canvas id="kt_profile_mini_chart_1" width="50" height="40"
                                            style="display: block;"></canvas>
                                </div>
                                <div class="kt-profile__stats-item-label">Earliest Calving Date</div>
                                <div class="kt-profile__stats-item-chart">
                                    <span><?= AnimalEvent::getEventEarlyDate($model->id, AnimalEvent::EVENT_TYPE_CALVING) ?></span>
                                    <canvas id="kt_profile_mini_chart_1" width="50" height="40"
                                            style="display: block;"></canvas>
                                </div>

                            </div>
                            <div class="kt-profile__stats-item">
                                <div class="kt-profile__stats-item-label">Last Milking Date</div>
                                <div class="kt-profile__stats-item-chart">
                                    <span><?= AnimalEvent::getEventLastDate($model->id, AnimalEvent::EVENT_TYPE_MILKING) ?></span>
                                    <canvas id="kt_profile_mini_chart_2" width="50" height="40"
                                            style="display: block;"></canvas>
                                </div>
                                <div class="kt-profile__stats-item-label">Earliest Milking Date</div>
                                <div class="kt-profile__stats-item-chart">
                                    <span><?= AnimalEvent::getEventEarlyDate($model->id, AnimalEvent::EVENT_TYPE_MILKING) ?></span>
                                    <canvas id="kt_profile_mini_chart_1" width="50" height="40"
                                            style="display: block;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-12 col-lg-3 col-xl-3">
                        <div class="kt-profile__stats">
                            <div class="kt-profile__stats-item">
                                <div class="kt-profile__stats-item-label">Last Weight Date</div>
                                <div class="kt-profile__stats-item-chart">
                                    <span><?= AnimalEvent::getEventLastDate($model->id, AnimalEvent::EVENT_TYPE_WEIGHTS) ?></span>
                                    <canvas id="kt_profile_mini_chart_1" width="50" height="40"
                                            style="display: block;"></canvas>
                                </div>
                                <div class="kt-profile__stats-item-label">Early Weight Date</div>
                                <div class="kt-profile__stats-item-chart">
                                    <span><?= AnimalEvent::getEventEarlyDate($model->id, AnimalEvent::EVENT_TYPE_WEIGHTS) ?></span>
                                    <canvas id="kt_profile_mini_chart_1" width="50" height="40"
                                            style="display: block;"></canvas>
                                </div>
                            </div>
                            <div class="kt-profile__stats-item">
                                <div class="kt-profile__stats-item-label">Last Health Date</div>
                                <div class="kt-profile__stats-item-chart">
                                    <span><?= AnimalEvent::getEventLastDate($model->id, AnimalEvent::EVENT_TYPE_HEALTH) ?></span>
                                    <canvas id="kt_profile_mini_chart_2" width="50" height="40"
                                            style="display: block;"></canvas>
                                </div>
                                <div class="kt-profile__stats-item-label">Early Health Date</div>
                                <div class="kt-profile__stats-item-chart">
                                    <span><?= AnimalEvent::getEventEarlyDate($model->id, AnimalEvent::EVENT_TYPE_HEALTH) ?></span>
                                    <canvas id="kt_profile_mini_chart_1" width="50" height="40"
                                            style="display: block;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="kt-profile__nav">
            <ul class="nav nav-tabs nav-tabs-line my-nav" role="tablist">
                <?php if (($model->animal_type == Animal::ANIMAL_TYPE_COW)): ?>
                    <li class="nav-item">
                        <a class="nav-link" title="Click To View"
                           href="<?= Url::to(['animal-event/index', 'animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_CALVING, 'country_id' => $model->country_id]) ?>"
                           role="tab">
                            <?= Lang::t('Calving') ?>
                            <span class="badge badge-secondary badge-pill">
                        <?= AnimalEvent::getCount(['animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_CALVING, 'country_id' => $model->country_id]) ?>
                    </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" title="Click To View"
                           href="<?= Url::to(['animal-event/index', 'animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_MILKING]) ?>"
                           role="tab">
                            <?= Lang::t('Milk Collection') ?>
                            <span class="badge badge-secondary badge-pill">
                        <?= AnimalEvent::getCount(['animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_MILKING, 'country_id' => $model->country_id]) ?>
                    </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" title="Click To View"
                           href="<?= Url::to(['animal-event/index', 'animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_AI, 'country_id' => $model->country_id]) ?>"
                           role="tab">
                            <?= Lang::t('AI') ?>
                            <span class="badge badge-secondary badge-pill">
                        <?= AnimalEvent::getCount(['animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_AI, 'country_id' => $model->country_id]) ?>
                    </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="Click To View"
                           href="<?= Url::to(['animal-event/index', 'animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS, 'country_id' => $model->country_id]) ?>"
                           role="tab">
                            <?= Lang::t('Pregnancy Diagnosis') ?>
                            <span class="badge badge-secondary badge-pill">
                        <?= AnimalEvent::getCount(['animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS, 'country_id' => $model->country_id]) ?>
                    </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="Click To View"
                           href="<?= Url::to(['animal-event/index', 'animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_SYNCHRONIZATION, 'country_id' => $model->country_id]) ?>"
                           role="tab">
                            <?= Lang::t('Synchronization') ?>
                            <span class="badge badge-secondary badge-pill">
                        <?= AnimalEvent::getCount(['animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_SYNCHRONIZATION, 'country_id' => $model->country_id]) ?>
                    </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="true"
                           aria-expanded="false">
                            <?= Lang::t('More Events') ?>
                        </a>
                        <div class="dropdown-menu" x-placement="bottom-start">
                            <a class="dropdown-item" title="Click To View"
                               href="<?= Url::to(['animal-event/index', 'animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS]) ?>">
                                <?= Lang::t('Weights') ?>
                                <span class="badge badge-secondary badge-pill">
                        <?= AnimalEvent::getCount(['animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS]) ?>
                    </span>

                            </a>
                            <a class="dropdown-item" title="Click To View"
                               href="<?= Url::to(['animal-event/index', 'animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_HEALTH]) ?>">
                                <?= Lang::t('Health') ?>
                                <span class="badge badge-secondary badge-pill">
                        <?= AnimalEvent::getCount(['animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_HEALTH]) ?>
                    </span>
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" title="Click To View"
                           href="<?= Url::to(['animal-event/index', 'animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS]) ?>"
                           role="tab">

                            <?= Lang::t('Weights') ?>
                            <span class="badge badge-secondary badge-pill">
                        <?= AnimalEvent::getCount(['animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS]) ?>
                    </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" title="Click To View"
                           href="<?= Url::to(['animal-event/index', 'animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_HEALTH]) ?>"
                           role="tab">

                            <?= Lang::t('Health') ?>
                            <span class="badge badge-secondary badge-pill">
                        <?= AnimalEvent::getCount(['animal_id' => $model->id, 'event_type' => AnimalEvent::EVENT_TYPE_HEALTH]) ?>
                    </span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="true"
                       aria-expanded="false"><?= Lang::t('Actions') ?></a>
                    <div class="dropdown-menu" x-placement="bottom-start">
                        <a class="dropdown-item" href="<?= Url::to(['animal/update', 'id' => $model->id]) ?>">
                            <i class="fa fa-pencil text-success"></i><?= Lang::t('Update Details') ?>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
<?php