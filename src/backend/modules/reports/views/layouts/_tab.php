<?php

use backend\modules\reports\Constants;
use backend\modules\reports\models\AdhocReport;
use common\helpers\Lang;
use yii\helpers\Url;

$activeStatus = Yii::$app->request->get('status');
?>
<ul class="nav nav-tabs my-nav" role="tablist">
    <?php if (Yii::$app->user->canView(Constants::RES_REPORTS)): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($activeStatus == AdhocReport::STATUS_QUEUED) ? 'active' : '' ?>"
               href="<?= Url::to(['adhoc-report/index', 'status' => AdhocReport::STATUS_QUEUED]) ?>">
                <?= Lang::t('Queued Reports') ?>
                <span class="badge badge-secondary badge-pill">
                    <?= number_format(AdhocReport::getCount(['status' => AdhocReport::STATUS_QUEUED])) ?>
                </span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (Yii::$app->user->canView(Constants::RES_REPORTS)): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($activeStatus == AdhocReport::STATUS_PROCESSING) ? 'active' : '' ?>"
               href="<?= Url::to(['adhoc-report/index', 'status' => AdhocReport::STATUS_PROCESSING]) ?>">
                <?= Lang::t('Running Reports') ?>
            </a>
        </li>
    <?php endif; ?>
    <?php if (Yii::$app->user->canView(Constants::RES_REPORTS)): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($activeStatus == AdhocReport::STATUS_COMPLETED) ? 'active' : '' ?>"
               href="<?= Url::to(['adhoc-report/index', 'status' => AdhocReport::STATUS_COMPLETED]) ?>">
                <?= Lang::t('Completed Reports') ?>
            </a>
        </li>
    <?php endif; ?>
</ul>