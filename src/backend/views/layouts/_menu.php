<?php

use backend\modules\auth\Session;
use backend\modules\core\Constants;
use backend\modules\core\models\Organization;
use common\helpers\Lang;
use yii\helpers\Url;

$countries = Organization::find()->orderBy(['code' => SORT_ASC])->all();
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
            <?php if (Yii::$app->user->canView(Constants::RES_ANIMAL)): ?>
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
                        <?php foreach ($countries as $country): ?>
                            <?php if (Session::getOrgId() == $country->id || Session::isPrivilegedAdmin()): ?>
                                <li class="kt-menu__item">
                                    <a href="<?= Url::to(['/core/animal/index', 'org_id' => $country->id]) ?>"
                                       class="kt-menu__link ">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text"><?= Lang::t('{country}', ['country' => $country->name]) ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    </div>
                </li>
            <?php endif; ?>
            <?php
            $eventControllers = ['core/animal-event', 'core/calving-event', 'core/milking-event', 'core/insemination-event', 'core/pd-event',
                'core/synchronization-event', 'core/weight-event', 'core/health-event', 'core/feeding-event', 'core/exits-event',
            ];
            $animalEventsActive = in_array(Yii::$app->controller->uniqueId, $eventControllers);
            ?>
            <?php if (Yii::$app->user->canView(Constants::RES_ANIMAL_EVENTS)): ?>
                <li class="kt-menu__item kt-menu__item--submenu <?= $animalEventsActive ? 'kt-menu__item--open kt-menu__item--here' : '' ?>"
                    data-ktmenu-submenu-toggle="hover">
                    <a href="#" class="kt-menu__link kt-menu__toggle">
                        <i class="kt-menu__link-icon far fa-calendar"></i>
                        <span class="kt-menu__link-text">ANIMALS EVENTS</span>
                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    <div class="kt-menu__submenu">
                        <span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <?php foreach ($countries as $country): ?>
                                <?php if (Session::getOrgId() == $country->id || Session::isPrivilegedAdmin()): ?>
                                    <li class="kt-menu__item">
                                        <a href="<?= Url::to(['/core/event-list/index', 'org_id' => $country->id]) ?>"
                                           class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text"><?= Lang::t('{country}', ['country' => $country->name]) ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->canView(Constants::RES_HERD)): ?>
                <li class="kt-menu__item  kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/herd' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                    <a href="#" class="kt-menu__link kt-menu__toggle">
                        <i class="kt-menu__link-icon far fa-cow"></i>
                        <span class="kt-menu__link-text">HERDS</span>
                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    <div class="kt-menu__submenu">
                        <span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <?php foreach ($countries as $country): ?>
                                <?php if (Session::getOrgId() == $country->id || Session::isPrivilegedAdmin()): ?>
                                    <li class="kt-menu__item">
                                        <a href="<?= Url::to(['/core/herd/index', 'org_id' => $country->id]) ?>"
                                           class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text"><?= Lang::t('{country}', ['country' => $country->name]) ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </ul>
                    </div>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->canView(Constants::RES_FARM)): ?>
                <li class="kt-menu__item  kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/farm' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                    <a href="#" class="kt-menu__link kt-menu__toggle">
                        <i class="kt-menu__link-icon far fa-tractor"></i>
                        <span class="kt-menu__link-text">FARMS</span>
                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    <div class="kt-menu__submenu">
                        <span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <?php foreach ($countries as $country): ?>
                                <?php if (Session::getOrgId() == $country->id || Session::isPrivilegedAdmin()): ?>
                                    <li class="kt-menu__item">
                                        <a href="<?= Url::to(['/core/farm/index', 'org_id' => $country->id]) ?>"
                                           class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text"><?= Lang::t('{country}', ['country' => $country->name]) ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </ul>
                    </div>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->canView(Constants::RES_CLIENT)): ?>
                <li class="kt-menu__item kt-menu__item--submenu hidden">
                    <a href="<?= Url::to(['/core/client/index']) ?>" class="kt-menu__link">
                        <i class="kt-menu__link-icon far fa-users"></i>
                        <span class="kt-menu__link-text">CLIENTS/FARMERS</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->canView(Constants::RES_REPORT_BUILDER)): ?>
                <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'reports/builder' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                    <a href="#" class="kt-menu__link kt-menu__toggle">
                        <i class="kt-menu__link-icon far fa-chart-pie"></i>
                        <span class="kt-menu__link-text">REPORT BUILDER</span>
                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    <div class="kt-menu__submenu">
                        <span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <?php foreach ($countries as $country): ?>
                                <?php if (Session::getOrgId() == $country->id || Session::isPrivilegedAdmin()): ?>
                                    <li class="kt-menu__item">
                                        <a href="<?= Url::to(['/reports/builder/index', 'org_id' => $country->id]) ?>"
                                           class="kt-menu__link ">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                            <span class="kt-menu__link-text"><?= Lang::t('{country}', ['country' => $country->name]) ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->canView(Constants::RES_AD_HOC_REPORTS)): ?>
                <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'reports/adhoc-report' ? 'kt-menu__item--here' : '' ?>">
                    <a href="<?= Url::to(['/reports/adhoc-report/index']) ?>" class="kt-menu__link">
                        <i class="kt-menu__link-icon flaticon2-browser-2"></i>
                        <span class="kt-menu__link-text">AD-HOC REPORTS</span>
                    </a>
                </li>
            <?php endif; ?>
            <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">ADMINISTRATION</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
            </li>
            <?php if (Yii::$app->user->canView(Constants::RES_COUNTRY)): ?>
                <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/organization' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                    <a href="<?= Url::to(['/core/organization/index']) ?>" class="kt-menu__link">
                        <i class="kt-menu__link-icon far fa-globe-africa"></i>
                        <span class="kt-menu__link-text">COUNTRIES</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->canView(Constants::RES_USERS)): ?>
                <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'auth/user' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                    <a href="<?= Url::to(['/auth/user/index']) ?>" class="kt-menu__link">
                        <i class="kt-menu__link-icon far fa-users"></i>
                        <span class="kt-menu__link-text">USERS</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->canView(Constants::RES_SYSTEM_SETTINGS)): ?>
                <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'conf/settings' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                    <a href="<?= Url::to(['/conf/settings/index']) ?>" class="kt-menu__link">
                        <i class="kt-menu__link-icon far fa-cog"></i>
                        <span class="kt-menu__link-text">SETTINGS</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->canView(Constants::RES_ODK_JSON)): ?>
                <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/odk-json' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                    <a href="<?= Url::to(['/core/odk-json/index']) ?>" class="kt-menu__link">
                        <i class="kt-menu__link-icon far fa-file-alt"></i>
                        <span class="kt-menu__link-text">ODK JSON FILES</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->canView(Constants::RES_EXCEL_UPLOAD_STATUS)): ?>
                <li class="kt-menu__item kt-menu__item--submenu <?= Yii::$app->controller->uniqueId == 'core/excel-upload-status' ? 'kt-menu__item--open kt-menu__item--here' : '' ?>">
                    <a href="<?= Url::to(['/core/excel-upload-status/index']) ?>" class="kt-menu__link">
                        <i class="kt-menu__link-icon far fa-file-alt"></i>
                        <span class="kt-menu__link-text">EXCEL/CSV FILES</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<!-- begin:: Aside Footer -->
<div class="kt-aside__footer kt-grid__item" id="kt_aside_footer">
    <div class="kt-aside__footer-nav">
        <?php if (Yii::$app->user->canView(Constants::RES_SYSTEM_SETTINGS)): ?>
            <div class="kt-aside__footer-item">
                <a href="<?= Url::to(['/conf/settings/index']) ?>" class="btn btn-icon" title="Settings"><i
                            class="flaticon2-gear"></i></a>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->canView(Constants::RES_USERS)): ?>
            <div class="kt-aside__footer-item">
                <a href="<?= Url::to(['/auth/user/index']) ?>" class="btn btn-icon" title="Users"><i
                            class="flaticon2-avatar"></i></a>
            </div>
        <?php endif; ?>
        <div class="kt-aside__footer-item">
            <a href="#" class="btn btn-icon" title="Reports"><i class="flaticon2-pie-chart"></i></a>
        </div>
    </div>
</div>

<!-- end:: Aside Footer-->