<?php

use yii\helpers\Url;

?>
<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1"
         data-ktmenu-dropdown-timeout="500">
        <ul class="kt-menu__nav ">
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open kt-menu__item--here"
                aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon flaticon-home"></i>
                    <span class="kt-menu__link-text">Dashboard</span>
                </a>
            </li>
            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">Farmers</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                data-ktmenu-submenu-toggle="hover">
                <a href="#" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon flaticon2-list"></i>
                    <span class="kt-menu__link-text">Active Farmers</span>
                    <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--brand">2</span></span>
                </a>
            </li>
            <li class="kt-menu__item " aria-haspopup="true">
                <a href="#" class="kt-menu__link ">
                    <i class="kt-menu__link-icon fa fa-search"></i>
                    <span class="kt-menu__link-text">Search Farmers</span>
                </a>
            </li>
            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">Field Officers</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item " aria-haspopup="true">
                <a href="#" class="kt-menu__link ">
                    <i class="kt-menu__link-icon fa fa-search"></i>
                    <span class="kt-menu__link-text">Search Field Officers</span>
                </a>
            </li>
            <li class="kt-menu__section">
                <h4 class="kt-menu__section-text">Users</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                data-ktmenu-submenu-toggle="hover">
                <a href="#" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon flaticon2-list"></i>
                    <span class="kt-menu__link-text">Active Users</span>
                    <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--brand">2</span></span>
                </a>
            </li>
            <li class="kt-menu__item " aria-haspopup="true">
                <a href="#" class="kt-menu__link ">
                    <i class="kt-menu__link-icon fa fa-search"></i>
                    <span class="kt-menu__link-text">Search Users</span>
                </a>
            </li>
            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">Menu Item</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item " aria-haspopup="true">
                <a href="#" class="kt-menu__link ">
                    <i class="kt-menu__link-icon fa fa-search"></i>
                    <span class="kt-menu__link-text">Sub Menu Item</span>
                </a>
            </li>
            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">Reports</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item " aria-haspopup="true">
                <a href="#" class="kt-menu__link ">
                    <i class="kt-menu__link-icon fa fa-search"></i>
                    <span class="kt-menu__link-text">Generate Report</span>
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
            <a href="#" class="btn btn-icon" title="Users"><i class="flaticon2-avatar"></i></a>
        </div>
        <div class="kt-aside__footer-item">
            <a href="#" class="btn btn-icon" title="Reports"><i class="flaticon2-pie-chart"></i></a>
        </div>
    </div>
</div>

<!-- end:: Aside Footer-->