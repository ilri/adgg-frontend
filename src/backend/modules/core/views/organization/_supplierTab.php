<?php

use backend\modules\core\Constants;
use backend\modules\core\models\Organization;
use common\helpers\Lang;
use yii\helpers\Url;

/* @var $controller \backend\modules\core\controllers\OrganizationController */
$controller = Yii::$app->controller;
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= $controller->activeTab == Constants::TAB_ALL_SUPPLIERS ? ' active' : '' ?>"
           href="<?= Url::to(['organization/index', 'is_supplier' => 1, 'tab' => Constants::TAB_ALL_SUPPLIERS]) ?>">
            <?= Lang::t('All Suppliers') ?>
            <span class="badge badge-light badge-pill">
                <?= number_format(Organization::getCount(['is_supplier' => 1, 'status' => Organization::STATUS_ACTIVE])) ?>
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $controller->activeTab == Constants::TAB_DISTRIBUTORS ? ' active' : '' ?>"
           href="<?= Url::to(['organization/index', 'is_supplier' => 1, 'business_type' => Organization::BUSINESS_TYPE_DISTRIBUTOR, 'tab' => Constants::TAB_DISTRIBUTORS]) ?>">
            <?= Lang::t('Distributors') ?>
            <span class="badge badge-light badge-pill">
                <?= number_format(Organization::getCount(['is_supplier' => 1, 'status' => Organization::STATUS_ACTIVE, 'business_type' => Organization::BUSINESS_TYPE_DISTRIBUTOR])) ?>
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $controller->activeTab == Constants::TAB_MANUFACTURERS ? ' active' : '' ?>"
           href="<?= Url::to(['organization/index', 'is_supplier' => 1, 'business_type' => Organization::BUSINESS_TYPE_MANUFACTURER, 'tab' => Constants::TAB_MANUFACTURERS]) ?>">
            <?= Lang::t('Manufacturers') ?>
            <span class="badge badge-light badge-pill">
                <?= number_format(Organization::getCount(['is_supplier' => 1, 'status' => Organization::STATUS_ACTIVE, 'business_type' => Organization::BUSINESS_TYPE_MANUFACTURER])) ?>
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $controller->activeTab == Constants::TAB_PENDING_APPROVAL ? ' active' : '' ?>"
           href="<?= Url::to(['organization/index', 'is_supplier' => 1, 'status' => Organization::STATUS_PENDING_APPROVAL, 'tab' => Constants::TAB_PENDING_APPROVAL]) ?>">
            <?= Lang::t('Pending Approval') ?>
            <span class="badge badge-warning badge-pill">
                <?= number_format(Organization::getCount(['is_supplier' => 1, 'status' => Organization::STATUS_PENDING_APPROVAL])) ?>
            </span>
        </a>
    </li>
</ul>