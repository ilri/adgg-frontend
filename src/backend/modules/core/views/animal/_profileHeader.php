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
            <div class="col-md-12 col-lg-5 col-xl-4">
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
            <div class="col-md-12 col-lg-4 col-xl-4">
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
                        <a href="mailto:<?= $model->farm->email ?>" target="_blank" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon">
                            <i class="fas fa-envelope"></i> Email:</span>
                            <span class="kt-profile__contact-item-text"><?= Html::encode($model->farm->email) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-3 col-xl-4">
                <div class="kt-profile__stats">
                    <div class="kt-profile__stats-item">
                        <div class="kt-profile__stats-item-label">Last Calving Date</div>
                        <div class="kt-profile__stats-item-chart">
                            <span><?= DateUtils::formatToLocalDate($model->latest_calv_date ?? 'None') ?></span>
                            <canvas id="kt_profile_mini_chart_1" width="50" height="40"
                                    style="display: block;"></canvas>
                        </div>
                    </div>
                    <div class="kt-profile__stats-item">
                        <div class="kt-profile__stats-item-label">Last Milk Collection</div>
                        <div class="kt-profile__stats-item-chart">
                            <span><?= DateUtils::formatToLocalDate($model->latest_calv_type ?? 'None') ?></span>
                            <canvas id="kt_profile_mini_chart_2" width="50" height="40"
                                    style="display: block;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-profile__nav">
        <ul class="nav nav-tabs nav-tabs-line my-nav" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['#']) ?>"
                   role="tab">
                    <?= Lang::t('Calving') ?>
                    <span class="badge badge-secondary badge-pill">
                        <?= 0 ?>
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" role="tab">
                    <?= Lang::t('Milk Collection') ?>
                    <span class="badge badge-secondary badge-pill">
                        <?= 0 ?>
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" role="tab">
                    <?= Lang::t('AI') ?>
                    <span class="badge badge-secondary badge-pill">
                        <?= 0 ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['animal/update', 'id' => $model->id]) ?>" role="tab">
                    <?= Lang::t('Update Details') ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" role="tab">
                    <?= Lang::t('Number of Events') ?>
                    <span class="badge badge-secondary badge-pill">
                        <?= AnimalEvent::getCount(['animal_id' => $model->id]) ?>
                    </span>
                </a>
            </li>
            <?php if ((int)AnimalEvent::getCount(['animal_id' => $model->id]) !== 0): ?>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle " data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="true"><?= Lang::t('Events List') ?></a>
                <div class="dropdown-menu overflow-auto">
                    <ul class="overflow-auto">
                        <?php foreach ($model->events as $event): ?>
                            <?php if ($model->id == $event->animal_id): ?>
                                <a class="button"
                                   href="<?= \common\helpers\Url::to(['animal-event/view', 'id' => $event->id]) ?>"
                                   title="Click To View Details">
                                    <h4> <?= $event::decodeEventType($event->event_type) . '<br>' . '<div class="dropdown-divider"></div>' ?></h4>
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