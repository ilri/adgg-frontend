<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use common\helpers\Lang;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model Farm */
?>

<div class="kt-portlet kt-profile">
    <div class="kt-profile__content">
        <div class="row">
            <div class="col-md-12 col-lg-4 col-xl-4">
                <div class="kt-profile__main">
                    <div class="kt-profile__main-pic">
                        <i class="far fa-tractor fa-4x"></i>
                    </div>
                    <div class="kt-profile__main-info">
                        <div class="kt-profile__main-info-name"><?= Html::encode($model->name) ?></div>
                        <div class="kt-profile__main-info-position"><?= Html::encode($model->farm_type) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 col-xl-4">
                <div class="kt-profile__contact">
                    <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-twitter">
                            <i class="fas fa-user"></i> Farmer:</span>
                        <span class="kt-profile__contact-item-text"><?= Html::encode($model->farmer_name) ?></span>
                    </a>
                    <?php if (!empty($model->phone)): ?>
                        <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-whatsup">
                            <i class="fas fa-phone"></i> Phone:</span>
                            <span class="kt-profile__contact-item-text"><?= Html::encode($model->phone) ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($model->email)): ?>
                        <a href="mailto:<?= $model->email ?>" target="_blank" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon">
                            <i class="fas fa-envelope"></i> Email:</span>
                            <span class="kt-profile__contact-item-text"><?= Html::encode($model->email) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 col-xl-4">
                <div class="kt-profile__contact">
                    <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-twitter">
                            <i class="fas fa-user"></i> Field Agent:</span>
                        <span class="kt-profile__contact-item-text"><?= Html::encode($model->getRelationAttributeValue('fieldAgent', 'name')) ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-profile__nav">
        <ul class="nav nav-tabs nav-tabs-line my-nav" role="tablist">
            <li class="nav-item">
                <?php foreach ($model->animals as $animal): ?>
                <?php endforeach; ?>
                <a class="nav-link" href="<?= Url::to(['animal/index', 'farm_id' => $model->id]) ?>" role="tab"
                   title="Click To view">
                    <?= Lang::t('Animals') ?>
                    <span class="badge badge-secondary badge-pill">
                        <?= Animal::getCount(['farm_id' => $model->id]) ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false"><?= Lang::t('Actions') ?></a>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <a class="dropdown-item" href="<?= Url::to(['farm/update', 'id' => $model->id]) ?>">
                        <i class="fa fa-pencil text-success"></i><?= Lang::t('Update Details') ?>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>