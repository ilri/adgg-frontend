<?php

use backend\modules\auth\Session;
use backend\modules\conf\Constants;
use common\helpers\Lang;
use yii\helpers\Url;

?>
<li class="<?= $this->context->activeMenu === Constants::MENU_SETTINGS ? 'active' : '' ?>">
    <a href="#">
        <i class="fa fa-lg fa-fw fa-wrench"></i>
        <span class="menu-item-parent"><?= Lang::t('SETTINGS') ?></span>
    </a>
    <ul>
        <?php if (Yii::$app->user->canView(Constants::RES_SETTINGS)): ?>
            <li>
                <a href="<?= Url::to(['/conf/settings/index']) ?>">
                    <?= Lang::t('System Settings') ?>
                </a>
            </li>
            <li>
                <a href="<?= Url::to(['/core/bank/index']) ?>"><?= Lang::t('Master Records') ?></a>
            </li>
        <?php endif; ?>
        <?php if (Yii::$app->user->canView(\backend\modules\auth\Constants::RES_USER)): ?>
            <li>
                <a href="<?= Url::to(['/auth/user/index']) ?>"><?= Lang::t('User Management') ?></a>
            </li>
        <?php endif; ?>
        <?php if (Session::isDev()): ?>
            <li>
                <a href="<?= Url::to(['/conf/logs/runtime']) ?>"><?= Lang::t('Developer tools') ?></a>
            </li>
        <?php endif; ?>
    </ul>
</li>
