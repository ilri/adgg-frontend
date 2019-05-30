<?php

use backend\modules\auth\Session;
use backend\modules\conf\Constants;
use common\helpers\Lang;
use common\helpers\Url;

/* @var $this \yii\web\View */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
?>
<div class="kt-portlet">
    <div class="kt-portlet__body kt-portlet__body--fit">
        <ul class="kt-nav kt-nav--bold kt-nav--md-space kt-nav--v3 kt-margin-t-20 kt-margin-b-20 nav nav-tabs my-list-group"
            role="tablist">
            <li class="kt-nav__item">
                <a class="kt-nav__link" href="<?= Url::to(['/conf/settings/index']) ?>">
                    <span class="kt-nav__link-text"><?= Lang::t('General Settings') ?></span>
                </a>
            </li>
            <li class="kt-nav__item<?= ($controller->activeSubMenu === Constants::SUBMENU_REGISTRATION) ? ' kt-nav__item--active' : '' ?>">
                <a class="kt-nav__link" href="<?= Url::to(['/core/registration-document-type/index']) ?>">
                    <span class="kt-nav__link-text"><?= Lang::t('Registration Settings') ?></span>
                </a>
            </li>
            <?php if (!Session::isOrganization()): ?>
                <li class="kt-nav__item<?= ($controller->activeSubMenu === Constants::SUBMENU_EMAIL) ? ' kt-nav__item--active' : '' ?>">
                    <a href="<?= Url::to(['/conf/email/index']) ?>" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= Lang::t('Email Settings') ?></span>
                    </a>
                </li>
                <li class="kt-nav__item<?= ($controller->activeSubMenu === Constants::SUBMENU_MASTER_DATA) ? ' kt-nav__item--active' : '' ?>">
                    <a href="<?= Url::to(['/core/country/index']) ?>" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= Lang::t('Master Data') ?></span>
                    </a>
                </li>
                <li class="kt-nav__item<?= ($controller->activeSubMenu === Constants::SUBMENU_SMS) ? ' kt-nav__item--active' : '' ?>">
                    <a href="<?= Url::to(['/conf/sms-template/index']) ?>" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= Lang::t('SMS Settings') ?></span>
                    </a>
                </li>
                <li class="kt-nav__item">
                    <a href="<?= Url::to(['/conf/notif/index']) ?>" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= Lang::t('Notifications Settings') ?></span>
                    </a>
                </li>
                <li class="kt-nav__item">
                    <a href="<?= Url::to(['/conf/security-settings/password']) ?>" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= Lang::t('Security Settings') ?></span>
                    </a>
                </li>
                <li class="kt-nav__item">
                    <a href="<?= Url::to(['/conf/settings/google-map']) ?>" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= Lang::t('Google Map Settings') ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (Session::isDev()): ?>
                <li class="kt-nav__separator"></li>
                <li class="kt-nav__item">
                    <a href="<?= Url::to(['/conf/number-format/index']) ?>" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= Lang::t('Numbering formats') ?></span>
                    </a>
                </li>
                <li class="kt-nav__item">
                    <a href="<?= Url::to(['/conf/logs/runtime']) ?>" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= Lang::t('Runtime Logs') ?></span>
                    </a>
                </li>
                <li class="kt-nav__item">
                    <a href="<?= Url::to(['/conf/job-manager/index']) ?>" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= Lang::t('Cron Jobs Manager') ?></span>
                    </a>
                </li>
                <li class="kt-nav__item hidden">
                    <a href="<?= Url::to(['/help/help-modules/index']) ?>" class="kt-nav__link">
                        <span class="kt-nav__link-text"><?= Lang::t('Manage System Manual') ?></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>