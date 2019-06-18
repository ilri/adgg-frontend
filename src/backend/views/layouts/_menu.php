<?php

use backend\modules\core\models\Organization;
use yii\helpers\Url;

?>
<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1"
         data-ktmenu-dropdown-timeout="500">
        <ul class="kt-menu__nav ">
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open kt-menu__item--here">
                <a href="<?= Yii::$app->homeUrl ?>" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon fas fa-home"></i>
                    <span class="kt-menu__link-text">DASHBOARD</span>
                </a>
            </li>

            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">ANIMALS, FARMS AND CLIENTS</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="#" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-cow"></i>
                    <span class="kt-menu__link-text">ANIMALS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="#" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-tractor"></i>
                    <span class="kt-menu__link-text">FARMS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="#" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-users"></i>
                    <span class="kt-menu__link-text">CLIENTS/PEOPLE</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="#" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-calendar-day"></i>
                    <span class="kt-menu__link-text">ANIMAL EVENTS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="#" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-chart-pie"></i>
                    <span class="kt-menu__link-text">REPORTS</span>
                </a>
            </li>

            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">ADMINISTRATION</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="<?= Url::to(['/core/organization/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-globe-africa"></i>
                    <span class="kt-menu__link-text">COUNTRIES</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="<?= Url::to(['/auth/user/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-users"></i>
                    <span class="kt-menu__link-text">USERS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="<?= Url::to(['/conf/settings/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-cog"></i>
                    <span class="kt-menu__link-text">SETTINGS</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- begin:: Aside Footer -->
<div class="kt-aside__footer kt-grid__item" id="kt_aside_footer">
    <div class="kt-aside__footer-nav">
        <div class="kt-aside__footer-item">
            <a href="<?= Url::to(['/conf/settings/index']) ?>" class="btn btn-icon" title="Settings"><i
                        class="flaticon2-gear"></i></a>
        </div>
        <div class="kt-aside__footer-item">
            <a href="<?= Url::to(['/auth/user/index']) ?>" class="btn btn-icon" title="Users"><i
                        class="flaticon2-avatar"></i></a>
        </div>
        <div class="kt-aside__footer-item">
            <a href="#" class="btn btn-icon" title="Reports"><i class="flaticon2-pie-chart"></i></a>
        </div>
    </div>
</div>

<!-- end:: Aside Footer-->