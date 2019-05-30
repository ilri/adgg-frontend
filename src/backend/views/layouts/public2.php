<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $controller \backend\controllers\BackendController */
\backend\assets\ModulesAsset::register($this);
\backend\assets\LoginAsset::register($this);
$controller = Yii::$app->controller;
$backgroundImages = [
    Yii::$app->view->theme->baseUrl . '/assets/img/login/banner4.jpg',
    Yii::$app->view->theme->baseUrl . '/assets/img/login/banner.jpg',
    Yii::$app->view->theme->baseUrl . '/assets/img/login/banner3.jpg',
    Yii::$app->view->theme->baseUrl . '/assets/img/login/banner2.jpg',
];
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
        <title><?= !empty($this->title) ? Html::encode($this->title) : \backend\modules\conf\settings\SystemSettings::getAppName() ?></title>
        <?php $this->head(); ?>
    </head>
    <body class="kt-login-v1--enabled kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">
    <?php $this->beginBody() ?>
    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid__item  kt-grid__item--fluid kt-grid kt-grid--hor kt-login-v1" id="kt_login_v1">

            <!--begin::Item-->
            <div class="kt-grid__item">

                <!--begin::Heade-->
                <div class="kt-login-v1__head">
                    <div class="kt-login-v1__logo">
                        <a href="<?= Yii::$app->homeUrl ?>">
                            <img src="<?= Yii::$app->view->theme->baseUrl ?>/assets/img/login/logo.png" alt=""/>
                        </a>
                    </div>
                    <div class="kt-login-v1__signup">
                        <?php if (Yii::$app->controller->route !== 'auth/auth/register'): ?>
                            <h4 class="kt-login-v1__heading">Don't have an account?</h4>
                            <a href="<?= Url::to(['auth/register']) ?>">Sign Up</a>
                        <?php else: ?>
                            <h4 class="kt-login-v1__heading">Already have an account?</h4>
                            <a href="<?= Url::to(['auth/login']) ?>">Sign In</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!--begin::Head-->
            </div>

            <!--end::Item-->

            <!--begin::Item-->
            <div class="kt-grid__item  kt-grid kt-grid--ver  kt-grid__item--fluid">

                <!--begin::Body-->
                <div class="kt-login-v1__body" id="kt-login-v1-body"
                     style="background-image: url(<?= Yii::$app->view->theme->baseUrl ?>/assets/img/login/banner4.jpg);background-size: cover;background-position: center top;"
                     data-images="<?= htmlspecialchars(Json::encode($backgroundImages)) ?>">

                    <!--begin::Section-->


                    <!--end::Separator-->

                    <!--begin::Wrapper-->
                    <div class="kt-login-v1__wrapper">
                        <div class="kt-login-v1__container">
                            <div class="kt-portlet kt-portlet--height-fluid kt-widget-13">
                                <div class="kt-portlet__body">
                                    <?= $content; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--end::Wrapper-->
                </div>

                <!--begin::Body-->
            </div>

            <!--end::Item-->

            <!--begin::Item-->
            <div class="kt-grid__item">
                <div class="kt-login-v1__footer">
                    <div class="kt-login-v1__menu">
                        <a href="#">Terms</a>
                        <a href="#">Website</a>
                        <a href="#">Contact</a>
                    </div>
                    <div class="kt-login-v1__copyright">
                        <a href="#">
                            &copy; <?= date('Y') ?> <?= \backend\modules\conf\settings\SystemSettings::getCompanyName() ?>
                        </a>
                    </div>
                </div>
            </div>
            <!--end::Item-->
        </div>
    </div>
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff",
                    "metal": "#c4c5d6",
                    "light": "#ffffff",
                    "accent": "#00c5dc",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995",
                    "focus": "#9816f4"
                },
                "base": {
                    "label": [
                        "#c5cbe3",
                        "#a1a8c3",
                        "#3d4465",
                        "#3e4466"
                    ],
                    "shape": [
                        "#f0f3ff",
                        "#d9dffa",
                        "#afb4d4",
                        "#646c9a"
                    ]
                }
            }
        };
    </script>
    <?php $this->endBody() ?>
    </body>

    </html>
<?php $this->endPage() ?>