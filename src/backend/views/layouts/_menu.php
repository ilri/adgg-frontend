<?php

use backend\modules\core\models\Organization;
use yii\helpers\Url;

?>
<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1"
         data-ktmenu-dropdown-timeout="500">
        <ul class="kt-menu__nav ">
            <li class="kt-menu__item kt-menu__item--submenu">
                <a href="<?= Yii::$app->homeUrl ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon fas fa-home"></i>
                    <span class="kt-menu__link-text">DASHBOARD</span>
                </a>
            </li>
            <!--<li class="kt-menu__item  kt-menu__item--submenu">
                <a href="#" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon far fa-calendar"></i>
                    <span class="kt-menu__link-text">DASHBOARDS</span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item">
                            <a href="<? /*= Url::to(['/dashboard/stats/dash','org_id'=>10]) */ ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">TANZANIA</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<? /*= Yii::$app->homeUrl */ ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">QUICK SUMMARY</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<? /*= Url::to(['/dashboard/stats/index']) */ ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">DETAILED SUMMARY</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>-->

            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">ANIMALS, FARMS AND CLIENTS</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/animal' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                <a href="#" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon far fa-cow"></i>
                    <span class="kt-menu__link-text">ANIMALS</span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/animal/index']) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">ALL ANIMALS</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/animal/index', 'org_id' => 10]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">TANZANIA</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/animal/index', 'org_id' => 11]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">KENYA</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="#"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">ETHIOPIA</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <?php
                $eventControllers = ['core/animal-event','core/calving-event', 'core/milking-event', 'core/insemination-event', 'core/pd-event',
                    'core/synchronization-event', 'core/weight-event', 'core/health-event', 'core/feeding-event', 'core/exits-event',
                ];
                $animalEventsActive = in_array(Yii::$app->controller->uniqueId, $eventControllers);
            ?>
            <li class="kt-menu__item kt-menu__item--submenu <?= $animalEventsActive ? 'kt-menu__item--open kt-menu__item--here' : '' ?>" data-ktmenu-submenu-toggle="hover">
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
                                <span class="kt-menu__link-text">Artificial Insemination</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/pd-event/index']) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Pregnancy Diagnosis</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/synchronization-event/index']) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Synchronization</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/weight-event/index']) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Weights</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/health-event/index']) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Health</span>
                            </a>
                        </li>
                        <li class="kt-menu__item hidden">
                            <a href="#"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Feeding</span>
                            </a>
                        </li>
                        <li class="kt-menu__item hidden">
                            <a href="#"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">Exits</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/herd' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                <a href="<?= Url::to(['/core/herd/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-cow"></i>
                    <span class="kt-menu__link-text">HERDS</span>
                </a>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/farm' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                <a href="#" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon far fa-tractor"></i>
                    <span class="kt-menu__link-text">FARMS</span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/farm/index']) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">ALL FARMS</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/farm/index', 'org_id' => 10]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">TANZANIA</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/core/farm/index', 'org_id' => 11]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">KENYA</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="#"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">ETHIOPIA</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu hidden">
                <a href="<?= Url::to(['/core/client/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-users"></i>
                    <span class="kt-menu__link-text">CLIENTS/FARMERS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'reports/builder' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                <a href="#" class="kt-menu__link kt-menu__toggle">
                    <i class="kt-menu__link-icon far fa-chart-pie"></i>
                    <span class="kt-menu__link-text">REPORT BUILDER</span>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/reports/builder/index', 'org_id' => 10]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">TANZANIA</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="<?= Url::to(['/reports/builder/index', 'org_id' => 11]) ?>"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">KENYA</span>
                            </a>
                        </li>
                        <li class="kt-menu__item">
                            <a href="#"
                               class="kt-menu__link ">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text">ETHIOPIA</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'reports/adhoc-report' ? 'kt-menu__item--here' : '' ?>">
                <a href="<?= Url::to(['/reports/adhoc-report/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon flaticon2-browser-2"></i>
                    <span class="kt-menu__link-text">AD-HOC REPORTS</span>
                </a>
            </li>

            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">ADMINISTRATION</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/organization' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                <a href="<?= Url::to(['/core/organization/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-globe-africa"></i>
                    <span class="kt-menu__link-text">COUNTRIES</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'auth/user' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                <a href="<?= Url::to(['/auth/user/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-users"></i>
                    <span class="kt-menu__link-text">USERS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'conf/settings' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                <a href="<?= Url::to(['/conf/settings/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-cog"></i>
                    <span class="kt-menu__link-text">SETTINGS</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/odk-json' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                <a href="<?= Url::to(['/core/odk-json/index']) ?>" class="kt-menu__link">
                    <i class="kt-menu__link-icon far fa-file-alt"></i>
                    <span class="kt-menu__link-text">ODK JSON FILES</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/excel-upload-status' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
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