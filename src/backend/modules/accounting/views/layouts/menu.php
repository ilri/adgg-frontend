<?php

use backend\modules\accounting\Constants;
use common\helpers\Lang;
use yii\helpers\Url;

/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
?>
<?php if (Yii::$app->user->canView(Constants::RES_ACCOUNTING)): ?>
    <li class="<?= $controller->isMenuActive(Constants::MENU_ACCOUNTING) ? 'active' : '' ?>">
        <a href="<?= Url::to(['#']) ?>">
            <i class="fa fa-lg fa-fw fa-book"></i>
            <span class="menu-item-parent"><?= Lang::t('ACCOUNTING') ?></span>
        </a>
    </li>
<?php endif; ?>