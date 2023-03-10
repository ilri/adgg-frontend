<?php

use common\helpers\Lang;
use yii\helpers\Url;

?>
<ul class="nav nav-tabs my-nav" role="tablist">
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['sms-template/index']) ?>"><?= Lang::t('SMS Templates') ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['sms-outbox/index']) ?>"><?= Lang::t('SMS Outbox') ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['sms-template/settings']) ?>"><?= Lang::t('SMS Settings') ?></a>
    </li>
</ul>