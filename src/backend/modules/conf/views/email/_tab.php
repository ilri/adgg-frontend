<?php

use common\helpers\Lang;
use yii\helpers\Url;

?>
<ul class="nav nav-tabs my-nav" role="tablist">
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['email/index']) ?>"><?= Lang::t('Email Templates') ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['email/settings']) ?>"><?= Lang::t('Email Settings') ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['email-outbox/index']) ?>"><?= Lang::t('Email Outbox') ?></a>
    </li>
</ul>