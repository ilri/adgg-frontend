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
                <h4 class="kt-menu__section-text">TRANSACTIONS &amp; FINANCE</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover">
                <a href="#" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon fas fa-shopping-cart"></i>
                    <span class="kt-menu__link-text">ORDERS</span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent">
                            <span class="kt-menu__link"><span class="kt-menu__link-text">ORDERS</span></span>
                        </li>
                        <li class="kt-menu__item">
                            <a href="#" class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Pending Orders</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="#" class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Approved Orders</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="#" class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Fulfilled Orders</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="#" class="kt-menu__link">
                    <i class="kt-menu__link-icon fas fa-percent"></i>
                    <span class="kt-menu__link-text">REBATES</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="#" class="kt-menu__link">
                    <i class="kt-menu__link-icon fas fa-hand-holding-usd"></i>
                    <span class="kt-menu__link-text">CREDITS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="#" class="kt-menu__link">
                    <i class="kt-menu__link-icon fas fa-percent"></i>
                    <span class="kt-menu__link-text">DISCOUNTS</span>
                </a>
            </li>
            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">RELATIONSHIP MANAGEMENT</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover">
                <a href="#" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon fas fa-clinic-medical"></i>
                    <span class="kt-menu__link-text">MEMBERS</span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent">
                            <span class="kt-menu__link"><span class="kt-menu__link-text">MEMBERS</span></span>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/organization/index', 'is_member' => 1]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">All Members</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/organization/index', 'is_member' => 1, 'business_type' => Organization::BUSINESS_TYPE_PHARMACY]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Pharmacies</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/organization/index', 'is_member' => 1, 'business_type' => Organization::BUSINESS_TYPE_HOSPITAL]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Hospitals</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/organization/index', 'is_member' => 1, 'business_type' => Organization::BUSINESS_TYPE_CLINIC]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Clinics</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover">
                <a href="#" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon fas fa-building"></i>
                    <span class="kt-menu__link-text">SUPPLIERS</span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent">
                            <span class="kt-menu__link"><span class="kt-menu__link-text">SUPPLIERS</span></span>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/organization/index', 'is_supplier' => 1]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">All Suppliers</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/organization/index', 'is_supplier' => 1, 'business_type' => Organization::BUSINESS_TYPE_DISTRIBUTOR]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Distributors</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/organization/index', 'is_supplier' => 1, 'business_type' => Organization::BUSINESS_TYPE_MANUFACTURER]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Manufacturers</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">ADMINISTRATION</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="#" class="kt-menu__link">
                    <i class="kt-menu__link-icon fas fa-medkit"></i>
                    <span class="kt-menu__link-text">PRODUCTS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="<?= Url::to(['/auth/user/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon fas fa-users"></i>
                    <span class="kt-menu__link-text">USERS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="<?= Url::to(['/conf/settings/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon2-gear"></i>
                    <span class="kt-menu__link-text">SETTINGS</span>
                </a>
            </li>
            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">REPORTS</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="#" class="kt-menu__link">
                    <i class="kt-menu__link-icon fas fa-chart-line"></i>
                    <span class="kt-menu__link-text">REPORTS</span>
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