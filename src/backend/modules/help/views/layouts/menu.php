<?php

use backend\modules\help\Constants;
use common\helpers\Lang;
use yii\helpers\Url;

?>
<li class="<?= $this->context->activeMenu === Constants::MENU_HELP ? 'active' : '' ?>">
    <a href="<?= Url::to(['/help/default/index']) ?>">
        <i class="fa fa-lg fa-fw fa-calculator"></i>
        <span class="menu-item-parent"><?= Lang::t('MANUAL') ?></span>
    </a>
</li>