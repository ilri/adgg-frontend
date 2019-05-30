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
        <a class="nav-link<?= $controller->activeTab == Constants::TAB_ALL_MEMBERS ? ' active' : '' ?>"
           href="<?= Url::to(['organization/index', 'is_member' => 1, 'tab' => Constants::TAB_ALL_MEMBERS]) ?>">
            <?= Lang::t('All Members') ?>
            <span class="badge badge-light badge-pill">
                <?= number_format(Organization::getCount(['is_member' => 1, 'status' => Organization::STATUS_ACTIVE])) ?>
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $controller->activeTab == Constants::TAB_PHARMACIES ? ' active' : '' ?>"
           href="<?= Url::to(['organization/index', 'is_member' => 1, 'business_type' => Organization::BUSINESS_TYPE_PHARMACY, 'tab' => Constants::TAB_PHARMACIES]) ?>">
            <?= Lang::t('Pharmacies') ?>
            <span class="badge badge-light badge-pill">
                <?= number_format(Organization::getCount(['is_member' => 1, 'status' => Organization::STATUS_ACTIVE, 'business_type' => Organization::BUSINESS_TYPE_PHARMACY])) ?>
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $controller->activeTab == Constants::TAB_HOSPITALS ? ' active' : '' ?>"
           href="<?= Url::to(['organization/index', 'is_member' => 1, 'business_type' => Organization::BUSINESS_TYPE_HOSPITAL, 'tab' => Constants::TAB_HOSPITALS]) ?>">
            <?= Lang::t('Hospitals') ?>
            <span class="badge badge-light badge-pill">
                <?= number_format(Organization::getCount(['is_member' => 1, 'status' => Organization::STATUS_ACTIVE, 'business_type' => Organization::BUSINESS_TYPE_HOSPITAL])) ?>
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $controller->activeTab == Constants::TAB_CLINICS ? ' active' : '' ?>"
           href="<?= Url::to(['organization/index', 'is_member' => 1, 'business_type' => Organization::BUSINESS_TYPE_CLINIC, 'tab' => Constants::TAB_CLINICS]) ?>">
            <?= Lang::t('Clinics') ?>
            <span class="badge badge-light badge-pill">
                <?= number_format(Organization::getCount(['is_member' => 1, 'status' => Organization::STATUS_ACTIVE, 'business_type' => Organization::BUSINESS_TYPE_CLINIC])) ?>
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $controller->activeTab == Constants::TAB_PENDING_APPROVAL ? ' active' : '' ?>"
           href="<?= Url::to(['organization/index', 'is_member' => 1, 'status' => Organization::STATUS_PENDING_APPROVAL, 'tab' => Constants::TAB_PENDING_APPROVAL]) ?>">
            <?= Lang::t('Pending Approval') ?>
            <span class="badge badge-warning badge-pill">
                <?= number_format(Organization::getCount(['is_member' => 1, 'status' => Organization::STATUS_PENDING_APPROVAL])) ?>
            </span>
        </a>
    </li>
</ul>