<?php

use backend\modules\auth\models\UserLevels;
use backend\modules\auth\models\Users;
use backend\modules\core\models\Organization;
use backend\modules\core\models\RegistrationDocument;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\helpers\Str;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model Organization */
?>

<div class="kt-portlet kt-profile">
    <div class="kt-profile__content">
        <div class="row">
            <div class="col-md-12 col-lg-5 col-xl-4">
                <div class="kt-profile__main">
                    <div class="kt-profile__main-pic">
                        <?php if (empty($model->logo)): ?>
                            <span class="kt-badge kt-badge--username kt-badge--lg kt-badge--brand kt-badge--bold"
                                  style="height: 60px;width: 60px;">
                            <?= Str::getInitials($model->name) ?>
                        </span>
                        <?php else: ?>
                            <img  class="img-thumbnail " src="<?= $model->getLogoUrl() ?>" alt="Logo" style="border-radius:0"/>
                        <?php endif; ?>
                    </div>
                    <div class="kt-profile__main-info">
                        <div class="kt-profile__main-info-name"><?= Html::encode($model->name) ?></div>
                        <div class="kt-profile__main-info-position"><?= $model->getDecodedBusinessType() ?></div>
                        <div class="kt-profile__main-info-position">
                            <?= Html::tag('span', $model->getDecodedStatus(), ['class' => $model->status === Organization::STATUS_ACTIVE ? 'badge badge-success badge-pill' : 'badge badge-warning badge-pill']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 col-xl-4">
                <div class="kt-profile__contact">
                    <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-twitter">
                            <i class="fas fa-user"></i></span>
                        <span class="kt-profile__contact-item-text"><?= Html::encode($model->getFullContactName(true, true)) ?></span>
                    </a>
                    <?php if (!empty($model->contact_phone)): ?>
                        <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-whatsup">
                            <i class="fas fa-phone"></i></span>
                            <span class="kt-profile__contact-item-text"><?= Html::encode($model->contact_phone) ?></span>
                        </a>
                    <?php endif; ?>
                    <a href="mailto:<?= $model->contact_email ?>" target="_blank" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon">
                            <i class="fas fa-envelope"></i></span>
                        <span class="kt-profile__contact-item-text"><?= Html::encode($model->contact_email) ?></span>
                    </a>
                </div>
            </div>
            <div class="col-md-12 col-lg-3 col-xl-4">
                <div class="kt-profile__stats">
                    <div class="kt-profile__stats-item">
                        <div class="kt-profile__stats-item-label">Last Login</div>
                        <div class="kt-profile__stats-item-chart">
                            <span><?= DateUtils::formatToLocalDate($model->last_login_date ?? 'None') ?></span>
                            <canvas id="kt_profile_mini_chart_1" width="50" height="40"
                                    style="display: block;"></canvas>
                        </div>
                    </div>
                    <?php if ($model->is_member): ?>
                        <div class="kt-profile__stats-item">
                            <div class="kt-profile__stats-item-label">Last Order</div>
                            <div class="kt-profile__stats-item-chart">
                                <span><?= DateUtils::formatToLocalDate($model->last_order_date ?? 'None') ?></span>
                                <canvas id="kt_profile_mini_chart_2" width="50" height="40"
                                        style="display: block;"></canvas>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-profile__nav">
        <ul class="nav nav-tabs nav-tabs-line my-nav" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['organization/view', 'id' => $model->id]) ?>" role="tab">
                    <?= Lang::t('Account details') ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['registration-document/index', 'org_id' => $model->uuid]) ?>"
                   role="tab">
                    <?= Lang::t('Registration Documents') ?>
                    <span class="badge badge-secondary badge-pill">
                        <?= RegistrationDocument::getCount(['org_id' => $model->id, 'is_active' => 1]) ?>
                    </span>
                </a>
            </li>
            <?php if ($model->is_member): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" role="tab">
                        <?= Lang::t('Orders') ?>
                    </a>
                </li>
            <?php elseif ($model->is_supplier): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" role="tab">
                        <?= Lang::t('Products') ?>
                    </a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['/auth/user/index', 'org_id' => $model->id]) ?>"
                   role="tab">
                    <?= Lang::t('Users') ?>
                    <span class="badge badge-secondary badge-pill">
                        <?= Users::getCount(['org_id' => $model->id, 'level_id' => UserLevels::LEVEL_COUNTRY]) ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false"><?= Lang::t('Actions') ?></a>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <?php if (Yii::$app->user->canUpdate()): ?>
                        <a class="dropdown-item" href="<?= Url::to(['organization/update', 'id' => $model->uuid]) ?>">
                            <?= Lang::t('Update details') ?>
                        </a>
                        <?php if (Yii::$app->user->canCreate(\backend\modules\core\Constants::RES_REGISTRATION_DOCUMENT)): ?>
                            <a class="dropdown-item" href="#"
                               data-href="<?= Url::to(['registration-document/create', 'org_id' => $model->uuid]) ?>"
                               data-toggle="modal">
                                <?= Lang::t('Add Document') ?>
                            </a>
                        <?php endif; ?>
                        <a class="dropdown-item"
                           href="<?= Url::to(['/auth/user/create', 'level_id' => UserLevels::LEVEL_COUNTRY, 'org_id' => $model->id]) ?>">
                            <?= Lang::t('Add User') ?>
                        </a>
                        <?php if ($model->is_member): ?>
                            <a class="dropdown-item"
                               href="#">
                                <?= Lang::t('Create Order') ?>
                            </a>
                        <?php elseif ($model->is_supplier): ?>
                            <a class="dropdown-item"
                               href="#">
                                <?= Lang::t('Create Product') ?>
                            </a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <?php if ($model->canBeApproved()): ?>
                            <a class="dropdown-item" href="#"
                               data-href="<?= Url::to(['organization/approve', 'id' => $model->id]) ?>"
                               data-toggle="modal">
                                <?= Lang::t('Approve') ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($model->status == Organization::STATUS_ACTIVE): ?>
                            <a class="dropdown-item simple-ajax-post" href="#"
                               data-href="<?= Url::to(['organization/change-status', 'id' => $model->id, 'status' => Organization::STATUS_SUSPENDED]) ?>"
                               data-confirm-message="<?= Lang::t('GENERIC_CONFIRM') ?>" data-refresh="1">
                                <?= Lang::t('Suspend Account') ?>
                            </a>
                        <?php elseif ($model->status == Organization::STATUS_SUSPENDED): ?>
                            <a class="dropdown-item simple-ajax-post" href="#"
                               data-href="<?= Url::to(['organization/change-status', 'id' => $model->id, 'status' => Organization::STATUS_ACTIVE]) ?>"
                               data-confirm-message="<?= Lang::t('GENERIC_CONFIRM') ?>" data-refresh="1">
                                <?= Lang::t('Activate Account') ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </li>
        </ul>
    </div>
</div>