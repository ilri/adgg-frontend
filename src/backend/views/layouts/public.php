<?php

use backend\modules\conf\settings\SystemSettings;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $controller \backend\controllers\BackendController */
\backend\assets\ModulesAsset::register($this);
$controller = Yii::$app->controller;
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= !empty($this->title) ? Html::encode($this->title) : SystemSettings::getAppName() ?></title>
        <?php $this->head(); ?>
    </head>
    <body class="kt-login-v2--enabled kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">
    <?php $this->beginBody() ?>
    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root"
         style="background-image: linear-gradient(to bottom, rgba(242,243,248, 0.6), rgba(242,243,248, 0.1), rgba(242,243,248, 0.3)), url('<?= Yii::$app->view->theme->baseUrl ?>/assets/img/backimage1.JPG');background-size: cover; background-position: center center;">
        <div class="kt-grid__item   kt-grid__item--fluid kt-grid  kt-grid kt-grid--hor kt-login-v2" id="kt_login_v2">
            <!--begin::Item-->
            <div class="kt-grid__item  kt-grid--hor">
                <!--begin::Heade-->
                <div class="kt-login-v2__head">
                    <div class="kt-login-v2__logo">
                        <a href="https://africadgg.wordpress.com/category/adgg/" target="_blank" data-toggle="tooltip" data-placement="right" title="Click to go to ADGG website" data-original-title="Click to go to ADGG website">
                            <img src="<?= Yii::$app->view->theme->baseUrl ?>/assets/img/login/adggLogo1.png"
                                 style="max-width: 250px; max-height: 200px;" alt="ADGG Logo"/>
                        </a>
                    </div>
                </div>
                <!--begin::Head-->
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <div class="kt-grid__item  kt-grid  kt-grid--ver  kt-grid__item--fluid">
                <!--begin::Body-->
                <div class="kt-login-v2__body">
                    <!--begin::Wrapper-->
                    <div class="kt-login-v2__wrapper" style="width: 85%;">
                        <?= $content; ?>
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--begin::Body-->
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <div class="kt-grid__item">
                <div class="kt-login-v2__footer">
                    <div class="mx-auto">
                        <div class="text-center pb-2">
                            <a href="https://www.ilri.org/" target="_blank" data-toggle="tooltip" data-placement="right" title="Click to go to ILRI website" data-original-title="Click to go to ILRI website">
                                <img class="p-3 border-right border-dark" style="max-width: 140px; max-height: 80px; border-width: 10px;" src="<?= Yii::$app->view->theme->baseUrl ?>/assets/img/ilri_logo.png" alt="">
                            </a>
                            <a href="https://www.cgiar.org/" target="_blank" data-toggle="tooltip" data-placement="right" title="Click to go to CGIAR website" data-original-title="Click to go to CGIAR website">
                                <img class="p-3" style="max-width: 140px; max-height: 80px" src="<?= Yii::$app->view->theme->baseUrl ?>/assets/img/cgiar_logo.png" alt="">
                            </a>
                        </div>
                        <div class="kt-login-v2__info text-center">
                        <span style="color: white; font-size: medium; font-weight: 500;">
                           &nbsp;&copy&nbsp;<?= date('Y'); ?>
                            <!--                          - Developed By <a href="https://competamillman.co.ke/"-->
                            <!--                                            target="_blank" style="color: #004730; font-weight: bold;">-->
                            <? //= \common\helpers\Lang::t('Competa Millman') ?><!--</a>-->
                       </span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Item-->
        </div>
    </div>

    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>