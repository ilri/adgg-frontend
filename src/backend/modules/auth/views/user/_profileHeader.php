<?php

use backend\modules\auth\Constants;
use backend\modules\auth\models\Users;
use common\helpers\Lang;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model backend\modules\auth\models\Users */

$can_update = Yii::$app->user->canUpdate(Constants::RES_USER) && $model->checkPermission(false, false, false, false);
?>

<div class="kt-portlet kt-profile">
    <div class="kt-profile__content">
        <div class="row">
            <div class="col-md-12 col-lg-5 col-xl-4">
                <div class="kt-profile__main">
                    <div class="kt-profile__main-pic">
                        <img src="<?= $model->getProfileImageUrl(256) ?>" alt="<?= Html::encode($model->name) ?>"/>
                    </div>
                    <div class="kt-profile__main-info">
                        <div class="kt-profile__main-info-name"><?= Html::encode($model->name) ?></div>
                        <div class="kt-profile__main-info-position"><?= Html::encode($model->level->name) ?></div>
                        <div class="kt-profile__main-info-position">
                            <?= Html::tag('span', $model->getDecodedStatus(), ['class' => $model->status === Users::STATUS_ACTIVE ? 'badge badge-success badge-pill' : 'badge badge-warning badge-pill']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 col-xl-4">
                <div class="kt-profile__contact">
                    <?php if (!empty($model->phone)): ?>
                        <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-whatsup">
                            <i class="fas fa-phone"></i></span>
                            <span class="kt-profile__contact-item-text"><?= Html::encode($model->phone) ?></span>
                        </a>
                    <?php endif; ?>
                    <a href="mailto:<?= $model->email ?>" target="_blank" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon">
                            <i class="fas fa-envelope"></i></span>
                        <span class="kt-profile__contact-item-text"><?= Html::encode($model->email) ?></span>
                    </a>
                    <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-twitter">
                            <i class="fas fa-at"></i></span>
                        <span class="kt-profile__contact-item-text"><?= Html::encode($model->username) ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-profile__nav">
        <ul class="nav nav-tabs nav-tabs-line my-nav" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="<?= Url::to(['/auth/user/view', 'id' => $model->id]) ?>" role="tab">
                    <?= Lang::t('Account details') ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false"><?= Lang::t('Actions') ?></a>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <?php if ($can_update): ?>
                        <a class="dropdown-item" href="<?= Url::to(['/auth/user/update', 'id' => $model->id]) ?>">
                            <?= Lang::t('Update details') ?>
                        </a>
                        <a class="dropdown-item"
                           href="<?= Url::to(['/auth/user/reset-password', 'id' => $model->id]) ?>">
                            <?= Lang::t('Reset password') ?>
                        </a>
                        <div class="dropdown-divider"></div>
                        <?php if ($model->status === Users::STATUS_ACTIVE): ?>
                            <a class="dropdown-item simple-ajax-post" href="#"
                               data-href="<?= Url::to(['/auth/user/change-status', 'id' => $model->id, 'status' => Users::STATUS_BLOCKED]) ?>"
                               data-confirm-message="<?= Lang::t('GENERIC_CONFIRM') ?>" data-refresh="1">
                                <?= Lang::t('Block Account') ?>
                            </a>
                        <?php else: ?>
                            <a class="dropdown-item simple-ajax-post" href="#"
                               data-href="<?= Url::to(['/auth/user/change-status', 'id' => $model->id, 'status' => Users::STATUS_ACTIVE]) ?>"
                               data-confirm-message="<?= Lang::t('GENERIC_CONFIRM') ?>" data-refresh="1">
                                <?= Lang::t('Activate Account') ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($model->isMyAccount()): ?>
                        <a class="dropdown-item" href="<?= Url::to(['/auth/user/change-password']) ?>">
                            <?= Lang::t('Change your password') ?>
                        </a>
                    <?php endif; ?>
                </div>
            </li>
        </ul>
    </div>
</div>