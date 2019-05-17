<?php

use backend\modules\reports\Constants;
use backend\modules\reports\models\Reports;
use common\helpers\Lang;
use yii\helpers\Url;

?>
<?php if (Yii::$app->user->canView(Constants::RES_REPORTS)): ?>
    <li class="<?= $this->context->activeMenu === Constants::MENU_REPORTS ? 'active' : '' ?>">
        <a href="#">
            <i class="fa fa-lg fa-fw fa-pie-chart"></i>
            <span class="menu-item-parent"><?= Lang::t('REPORTS') ?></span>
        </a>
        <ul>
            <li>
                <a href="<?= Url::to(['/reports/default/index']) ?>"><?= Lang::t('Reports Dashboard') ?></a>
            </li>
        </ul>
    </li>
<?php endif; ?>