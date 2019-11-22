<?php

use backend\modules\core\models\Organization;
use yii\helpers\Url;

?>
<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1"
         data-ktmenu-dropdown-timeout="500">
        <ul class="kt-menu__nav ">
            <li class="kt-menu__item  kt-menu__item--submenu">
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
                <a href="<?= Url::to(['/core/animal/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-cow"></i>
                    <span class="kt-menu__link-text">ANIMALS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover">
                <a href="#" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon far fa-calendar"></i>
                    <span class="kt-menu__link-text">ANIMALS EVENTS</span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent">
                            <span class="kt-menu__link"><span class="kt-menu__link-text">EVENTS</span></span>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/calving-event/index']) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Calving</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/milking-event/index']) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Milking</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/insemination-event/index']) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Insemination</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="#"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Pregnancy Diagnosis</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="#"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Synchronization</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="#"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Weights</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="#"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Health</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="<?= Url::to(['/core/herd/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-cow"></i>
                    <span class="kt-menu__link-text">HERDS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="<?= Url::to(['/core/farm/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-tractor"></i>
                    <span class="kt-menu__link-text">FARMS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu hidden">
                <a href="<?= Url::to(['/core/client/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-users"></i>
                    <span class="kt-menu__link-text">CLIENTS/FARMERS</span>
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
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="<?= Url::to(['/core/odk-json/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-file-alt"></i>
                    <span class="kt-menu__link-text">ODK JSON FILES</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="<?= Url::to(['/core/excel-upload-status/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-file-alt"></i>
                    <span class="kt-menu__link-text">EXCEL/CSV FILES</span>
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