<?php

use backend\modules\auth\Session;
use backend\modules\help\Constants;
use common\helpers\Lang;
use common\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Help & Documentation';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12" title="Click to view details">
        <div class="well">
            <h3><?= Lang::t('Help & Documentation') ?></h3>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <a href="<?= Url::to(['read']) ?>" class="kt-iconbox kt-iconbox--active">
                            <div class="kt-iconbox__icon">
                                <div class="kt-iconbox__icon-bg"></div>
                                <i class="far fa-chalkboard-teacher"></i>
                            </div>
                            <div class="kt-iconbox__title"><?= Lang::t('User Manual'); ?></div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <?php if (Yii::$app->user->canView(Constants::RES_ANDROID_APP_MANUAL)): ?>
                    <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <a href="<?= Url::to(['read', 'forAndroid' => true])?>"
                               class="kt-iconbox kt-iconbox--active">
                                <div class="kt-iconbox__icon">
                                    <div class="kt-iconbox__icon-bg"></div>
                                    <i class="far fa-mobile-alt"></i>
                                </div>
                                <div class="kt-iconbox__title"><?= Lang::t('Android App Manual'); ?></div>
                            </a>
                        </div>
                        <!--end::Portlet-->
                    </div>
                <?php endif; ?>
                <?php if (Yii::$app->user->canView(Constants::RES_API_DOC)): ?>
                    <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <a href="http://www.adgg.ilri.org/api-doc" class="kt-iconbox kt-iconbox--active"
                               target="_blank">
                                <div class="kt-iconbox__icon">
                                    <div class="kt-iconbox__icon-bg"></div>
                                    <i class="far fa-code"></i>
                                </div>
                                <div class="kt-iconbox__title"><?= Lang::t('API Documentation'); ?></div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <?php endif; ?>
                <?php if (Yii::$app->user->canView(Constants::RES_DB_DOC)): ?>
                <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                    <!--begin::Portlet-->
                    <div class="kt-portlet" title="Click to view details">
                        <a href="http://www.adgg.ilri.org/db-doc" class="kt-iconbox kt-iconbox--active" target="_blank">
                            <div class="kt-iconbox__icon">
                                <div class="kt-iconbox__icon-bg"></div>
                                <i class="far fa-database"></i>
                            </div>
                            <div class="kt-iconbox__title"><?= Lang::t('Database Documentation'); ?>                            </div>
                        </a>
                    </div>
                    <!--end::Portlet-->
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
