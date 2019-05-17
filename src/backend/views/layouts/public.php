<?php

use backend\modules\conf\settings\SystemSettings;
use backend\widgets\Alert;
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
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
            WebFont.load({
                google: {
                    "families": ["Poppins:300,400,500,600,700"]
                },
                active: function () {
                    sessionStorage.fonts = true;
                }
            });
        </script>
    </head>
    <body class="kt-login-v2--enabled kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">
    <?php $this->beginBody() ?>
    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid__item   kt-grid__item--fluid kt-grid  kt-grid kt-grid--hor kt-login-v2" id="kt_login_v2">

            <!--begin::Item-->
            <div class="kt-grid__item  kt-grid--hor">

                <!--begin::Heade-->
                <div class="kt-login-v2__head">
                    <div class="kt-login-v2__logo">
                        <a href="<?= Yii::$app->homeUrl ?>">
                            <img src="<?= Yii::$app->view->theme->baseUrl ?>/assets/img/login/adgg.jpg" alt=""/>
                        </a>
                    </div>
                    <div class="kt-login-v2__signup">
                        <a href="#" class="kt-link kt-font-brand">Move to ADGG Website</a>
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
                    <div class="kt-login-v2__wrapper">
                        <?= $content; ?>
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Image-->
                    <div class="kt-login-v2__image">
                        <img src="<?= Yii::$app->view->theme->baseUrl ?>/assets/img/bg_icon.svg" alt="">
                    </div>
                    <!--begin::Image-->
                </div>
                <!--begin::Body-->
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <div class="kt-grid__item">
                <div class="kt-login-v2__footer">
                    <div class="kt-login-v2__link">
                        <a href="#" class="kt-link kt-font-brand">Website</a>
                        <a href="#" class="kt-link kt-font-brand">Contact</a>
                    </div>
                    <div class="kt-login-v2__info">
                        <a href="#"
                           class="kt-link">&copy; <?= date('Y') ?> <?= SystemSettings::getCompanyName() ?></a>
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