<?php

use yii\helpers\Url;

?>
<div class="kt-header__topbar-item dropdown">
    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px, 0px"
         aria-expanded="true">
									<span class="kt-header__topbar-icon" id="button-show-notifications"
                                          data-mark-as-seen-url="<?= Url::to(['/conf/notif/mark-as-seen']) ?>"
                                          data-check-notif-url="<?= Url::to(['/conf/notif/fetch']) ?>">
										<i class="flaticon2-bell-alarm-symbol"></i>
										<span class="kt-badge kt-badge--dot kt-badge--notify kt-badge--sm kt-badge--brand hidden"></span>
									</span>
    </div>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-lg">
        <form>
            <div class="kt-head"
                 style="background-color: #004730">
                <h3 class="kt-head__title">Notifications</h3>
                <div class="kt-head__sub"><span class="kt-head__desc"><span
                                class="total-notif">0</span> notifications</span>
                </div>
            </div>
            <div class="kt-notification kt-margin-t-30 kt-margin-b-20 kt-scroll" id="ajax-notifications"
                 data-scroll="true"
                 data-height="270" data-mobile-height="220">
            </div>
        </form>
    </div>
</div>