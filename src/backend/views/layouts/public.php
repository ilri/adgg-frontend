<?php

use backend\modules\conf\settings\SystemSettings;
use backend\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

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
    <body class="kt-login-v2--enabled kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading" >
    <?php $this->beginBody() ?>
    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root" style="background-image: linear-gradient(to bottom, rgba(242,243,248, 0.8), rgba(242,243,248, -0.3)), url('<?= Yii::$app->view->theme->baseUrl ?>/assets/img/image 1.png');background-size: cover; background-position: center center;" >
        <div class="kt-grid__item   kt-grid__item--fluid kt-grid  kt-grid kt-grid--hor kt-login-v2" id="kt_login_v2">

            <!--begin::Item-->
            <div class="kt-grid__item  kt-grid--hor">

                <!--begin::Heade-->
                <div class="kt-login-v2__head" style="background-color: linear-gradient(to bottom, rgba(242,243,248, 1.29), rgba(242,243,248, 0.29)),">
                    <div class="kt-login-v2__logo">
                        <a href="<?= Yii::$app->homeUrl ?>">
                            <img src="<?= Yii::$app->view->theme->baseUrl ?>/assets/img/login/adggLogo1.png" style="max-width: 250px; max-height: 200px;" alt=""/>
                        </a>
                    </div>
                    <div class="kt-login-v2__signup">
                        <a href="https://africadgg.wordpress.com/category/adgg/" target="_blank" class="kt-link kt-font-brand">Move to ADGG Website</a>
                    </div>
                </div>
                <!--begin::Head-->
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <div class="kt-grid__item  kt-grid  kt-grid--ver  kt-grid__item--fluid">
                <!--begin::Body-->
                <div class="kt-login-v2__body" >
                    <!--begin::Wrapper-->
                    <div class="kt-login-v2__wrapper"style="">
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
                    <div class="kt-login-v2__link">
                        <a href="https://africadgg.wordpress.com/category/adgg/" target="_blank"
                           class="kt-link kt-font-brand" style="color: #004730;">Website</a>
                    </div>
                    <div class="kt-login-v2__info">

                        <span style="color: white;">
                           <?= \backend\modules\conf\settings\SystemSettings::getCompanyName() ?>
                          | <?= date('Y'); ?>&nbsp;&copy&nbsp;
                          - Developed By <a href="https://competamillman.co.ke/"
                                            target="_blank"><?= \common\helpers\Lang::t('Competa Millman') ?></a>
                       </span>
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