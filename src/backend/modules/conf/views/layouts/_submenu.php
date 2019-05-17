<?php

use backend\modules\auth\Session;
use common\helpers\Lang;
use common\helpers\Url;

/* @var $this \yii\web\View */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
?>
<div class="kt-portlet">
    <div class="kt-portlet__body">

        <!--begin::Section-->
        <div class="kt-section">
            <div class="kt-section__content">
                <ul class="kt-nav kt-nav--v2 kt-nav--lg-space kt-nav--bold kt-nav--lg-font my-list-group">
                    <li class="kt-nav__item">
                        <a href="<?= Url::to(['/conf/settings/index']) ?>" class="kt-nav__link">
                            <span class="kt-nav__link-text "><?= Lang::t('General Settings') ?></span>
                        </a>
                    </li>
                    <?php if (!Session::isOrganization()): ?>
                        <li class="kt-nav__item">
                            <a href="<?= Url::to(['/conf/email/index']) ?>" class="kt-nav__link">
                                <span class="kt-nav__link-text"><?= Lang::t('Email Settings') ?></span>
                            </a>
                        </li>
                        <li class="kt-nav__item">
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
                            <a href="<?= Url::to(['/conf/registration-settings/index']) ?>" class="kt-nav__link">
                                <span class="kt-nav__link-text"><?= Lang::t('Registration Settings') ?></span>
                            </a>
                        </li>
                        <li class="kt-nav__item">
                            <a href="<?= Url::to(['/conf/number-format/index']) ?>" class="kt-nav__link">
                                <span class="kt-nav__link-text"><?= Lang::t('Numbering formats') ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <!--end::Section-->
    </div>
</div>
