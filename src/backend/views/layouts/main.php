<?php

use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Country;
use backend\widgets\Alert;
use common\helpers\Lang;
use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Html;
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
    <body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-footer--fixed kt-page--loading">
    <?php $this->beginBody() ?>
    <!-- begin:: Header Mobile -->
    <?= $this->render('_headerMobile') ?>
    <!-- end:: Header Mobile -->
    <!-- begin:: Root -->
    <div class="kt-grid kt-grid--hor kt-grid--root">

        <!-- begin:: Page -->
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

            <!-- begin:: Aside -->
            <button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
            <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop"
                 id="kt_aside">

                <!-- begin::Aside Brand -->
                <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand" style="background-color: #f2f3f8;">
                    <div class="kt-aside__brand-logo">
                        <a href="<?= Yii::$app->homeUrl ?>">
                            <img alt="Logo"
                                 src="<?= Yii::$app->view->theme->baseUrl ?>/assets/img/login/<?= SystemSettings::getDefaultTheme() === SystemSettings::THEME_DEFAULT ? 'adggLogo1.png' : 'adggLogo1.png' ?>"
                                 style="max-height: 100px; max-width: 150px;"/>
                        </a>
                    </div>
                    <div class="kt-aside__brand-tools">
                        <button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left"
                                id="kt_aside_toggler"><span></span>
                        </button>
                    </div>
                </div>
                <!-- end:: Aside Brand -->

                <!-- begin:: Aside Menu -->
                <?= $this->render('_menu') ?>
                <!-- end:: Aside Menu -->
            </div>
            <!-- end:: Aside -->

            <!-- begin:: Wrapper -->
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

                <!-- begin:: Header -->
                <?= $this->render('_header') ?>
                <!-- end:: Header -->
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                    <!-- begin:: Subheader -->
                    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
                        <div class="kt-subheader__main">
                            <h3 class="kt-subheader__title"><?= $this->title ?></h3>
                            <span class="kt-subheader__separator kt-hidden"></span>

                            <?php if (!empty($this->params['breadcrumbs'])): ?>
                                <!-- breadcrumb -->
                                <?= Breadcrumbs::widget([
                                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- end:: Subheader -->
                    <!-- begin:: Content -->
                    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
                        <?= Alert::widget(); ?>
                        <?= $content; ?>
                    </div>
                    <!-- end:: Content -->
                </div>

                <!-- begin:: Footer -->
                <?= $this->render('_footer') ?>
                <!-- end:: Footer -->
            </div>

            <!-- end:: Wrapper -->
        </div>

        <!-- end:: Page -->
    </div>

    <!-- end:: Root -->

    <!-- begin:: Topbar Offcanvas Panels -->

    <!-- begin::Offcanvas Toolbar Quick Actions -->
    <div id="kt_offcanvas_toolbar_quick_actions" class="kt-offcanvas-panel">
        <div class="kt-offcanvas-panel__head">
            <h3 class="kt-offcanvas-panel__title">
                Quick Actions
            </h3>
            <a href="#" class="kt-offcanvas-panel__close" id="kt_offcanvas_toolbar_quick_actions_close"><i
                    class="flaticon2-delete"></i></a>
        </div>
        <div class="kt-offcanvas-panel__body">
            <div class="kt-grid-nav-v2">
                <?php if (Yii::$app->user->canView(\backend\modules\core\Constants::RES_ANIMAL)): ?>
                    <a href="<?= Url::to(['/core/animal/index']) ?>" class="kt-grid-nav-v2__item">
                        <div class="kt-grid-nav-v2__item-icon"><i class="far fa-cow"></i></div>
                        <div class="kt-grid-nav-v2__item-title"><?= Lang::t('Animals') ?></div>
                    </a>
                <?php endif; ?>
                <?php if (Yii::$app->user->canView(\backend\modules\core\Constants::RES_FARM)): ?>
                    <a href="<?= Url::to(['/core/farm/index']) ?>" class="kt-grid-nav-v2__item">
                        <div class="kt-grid-nav-v2__item-icon"><i class="far fa-tractor"></i></div>
                        <div class="kt-grid-nav-v2__item-title"><?= Lang::t('Farms') ?></div>
                    </a>
                <?php endif; ?>
                <?php if (Yii::$app->user->canView(\backend\modules\core\Constants::RES_CLIENT)): ?>
                    <a href="#" class="kt-grid-nav-v2__item">
                        <div class="kt-grid-nav-v2__item-icon"><i class="far fa-users"></i></div>
                        <div class="kt-grid-nav-v2__item-title"><?= Lang::t('Clients') ?></div>
                    </a>
                <?php endif; ?>
                <?php if (Yii::$app->user->canView(\backend\modules\core\Constants::RES_COUNTRY)): ?>

                    <a href="<?= Url::to(['/core/country/index']) ?>" class="kt-grid-nav-v2__item">
                        <div class="kt-grid-nav-v2__item-icon"><i class="far fa-calendar-day"></i></div>
                        <div class="kt-grid-nav-v2__item-title"><?= Lang::t('Countries') ?></div>
                    </a>
                <?php endif; ?>
                <?php if (Yii::$app->user->canView(\backend\modules\auth\Constants::RES_USER)): ?>
                    <a href="<?= Url::to(['/auth/user/index']) ?>" class="kt-grid-nav-v2__item">
                        <div class="kt-grid-nav-v2__item-icon"><i class="far fa-users"></i></div>
                        <div class="kt-grid-nav-v2__item-title"><?= Lang::t('Users') ?></div>
                    </a>
                <?php endif; ?>
                <?php if (Yii::$app->user->canView(\backend\modules\conf\Constants::RES_SETTINGS)): ?>
                    <a href="<?= Url::to(['/conf/settings/index']) ?>" class="kt-grid-nav-v2__item">
                        <div class="kt-grid-nav-v2__item-icon"><i class="far fa-cog"></i></div>
                        <div class="kt-grid-nav-v2__item-title"><?= Lang::t('Settings') ?></div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- end::Offcanvas Toolbar Quick Actions -->

    <!-- end:: Topbar Offcanvas Panels -->

    <!-- begin:: Scrolltop -->
    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="la la-arrow-up"></i>
    </div>
    <!-- begin::Global Config(global config for global JS sciprts) -->
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
    <!-- begin: Modal -->
    <div class="modal fade" id="my-bs-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <!--end: modal-->
    </body>
    </html>
<?php $this->endPage() ?>