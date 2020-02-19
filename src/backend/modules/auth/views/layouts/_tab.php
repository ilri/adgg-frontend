<?php

use backend\modules\auth\Constants;
use backend\modules\auth\models\Users;
use common\helpers\Lang;
use yii\helpers\Url;

/* @var $countryModel \backend\modules\core\models\Country */
?>
<ul class="nav nav-tabs my-nav" role="tablist">
    <?php if (Yii::$app->user->canView(Constants::RES_USER)): ?>
        <li class="nav-item">
            <a class="nav-link"
               href="<?= Url::to(['user/index', 'country_id' => !empty($countryModel) ? $countryModel->id : null]) ?>">
                <?= Lang::t('Users/Administrators') ?>
                <span
                    class="badge badge-secondary badge-pill"><?= number_format(Users::getCount(!empty($countryModel) ? ['status' => Users::STATUS_ACTIVE, 'country_id' => $countryModel->id] : ['status' => Users::STATUS_ACTIVE])) ?></span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (Yii::$app->user->canView(Constants::RES_ROLE)): ?>
        <li class="nav-item">
            <a class="nav-link"
               href="<?= Url::to(['role/index', 'country_id' => !empty($countryModel) ? $countryModel->id : null]) ?>">
                <?= Lang::t('Roles & Privileges') ?>
            </a>
        </li>
    <?php endif; ?>
    <?php if (Yii::$app->user->canView(Constants::RES_AUDIT_TRAIL)): ?>
        <li class="nav-item">
            <a class="nav-link"
               href="<?= Url::to(['audit-trail/index', 'country_id' => !empty($countryModel) ? $countryModel->id : null]) ?>">
                <?= Lang::t('Users Audit Trails') ?>
            </a>
        </li>
        <li class="nav-item hidden">
            <a class="nav-link"
               href="<?= Url::to(['audit-trail/index', 'country_id' => !empty($countryModel) ? $countryModel->id : null]) ?>">
                <?= Lang::t('Login Logs') ?>
            </a>
        </li>
    <?php endif; ?>
    <?php if (\backend\modules\auth\Session::isDev()): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= Url::to(['resource/index']) ?>">
                <?= Lang::t('System Resources') ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= Url::to(['user-level/index']) ?>">
                <?= Lang::t('Account Types') ?>
            </a>
        </li>
    <?php endif; ?>
</ul>