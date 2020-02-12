<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $user \backend\modules\auth\models\Users */
$user = Yii::$app->user->identity;
?>
<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

    <!-- begin:: Header Menu -->
    <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i
                class="la la-close"></i></button>
    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
        <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout- ">
        </div>
    </div>

    <!-- end:: Header Menu -->

    <!-- begin:: Header Topbar -->
    <div class="kt-header__topbar">
        <div class="kt-header__topbar-item">
            <a class="pt-3" href="<?= Url::to(['/help/help-content/index']) ?>">
            <div class="kt-header__topbar-wrapper">
                <span class="kt-header__topbar-icon"><i class="flaticon2-information"></i></span>
            </div>
            </a>
        </div>
        <!--begin: Notifications -->
        <?= $this->render('@confModule/views/notif/notif2') ?>
        <!--end: Notifications -->

        <!--begin: Quick Actions -->
        <div class="kt-header__topbar-item">
            <div class="kt-header__topbar-wrapper" id="kt_offcanvas_toolbar_quick_actions_toggler_btn">
                <span class="kt-header__topbar-icon"><i class="flaticon2-gear"></i></span>
            </div>
        </div>

        <!--end: Quick Actions -->

        <!--begin: User Bar -->
        <div class="kt-header__topbar-item kt-header__topbar-item--user">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px, 0px">
                <div class="kt-header__topbar-user">
                    <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                    <span class="kt-header__topbar-username kt-hidden-mobile"><?= Html::encode($user->name) ?></span>
                    <img alt="Avator" src="<?= $user->getProfileImageUrl(32) ?>"/>

                    <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                    <span class="kt-badge kt-badge--username kt-badge--lg kt-badge--brand kt-hidden kt-badge--bold">S</span>
                </div>
            </div>
            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-sm">
                <div class="kt-user-card kt-margin-b-40 kt-margin-b-30-tablet-and-mobile"
                     style="background-color: #5b206e">
                    <div class="kt-user-card__wrapper">
                        <div class="kt-user-card__pic">
                            <img alt="Avator" src="<?= $user->getProfileImageUrl(32) ?>"/>
                        </div>
                        <div class="kt-user-card__details">
                            <div class="kt-user-card__name"><?= Html::encode($user->name) ?></div>
                            <div class="kt-user-card__position"><?= Html::encode($user->getRelationAttributeValue('level', 'name')) ?></div>
                        </div>
                    </div>
                </div>
                <ul class="kt-nav kt-margin-b-10">
                    <li class="kt-nav__item">
                        <a href="<?= Url::to(['/auth/user/update', 'id' => Yii::$app->user->id]) ?>"
                           class="kt-nav__link">
                            <span class="kt-nav__link-icon"><i class="flaticon2-calendar-3"></i></span>
                            <span class="kt-nav__link-text">My Account</span>
                        </a>
                    </li>
                    <li class="kt-nav__item kt-nav__item--custom kt-margin-t-15">
                        <a href="<?= Url::to(['/auth/auth/logout']) ?>"
                           class="btn btn-label-brand btn-upper btn-sm btn-bold">Sign Out</a>
                    </li>
                </ul>
            </div>
        </div>
        <!--end: User Bar -->
    </div>

    <!-- end:: Header Topbar -->
</div>