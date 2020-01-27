<?php

use common\helpers\Lang;
use yii\helpers\Url;

?>
<ul class="nav nav-tabs my-nav">
    <li class="nav-item ">
        <a class="nav-link"
           href="<?= Url::to(['android-app/index']) ?>"><?= Lang::t('Manage Android App Versions') ?></a>
    </li>
    <li class="nav-item ">
        <a class="nav-link hidden" href="<?= Url::to(['oauth-client/index']) ?>"><?= Lang::t('App Settings') ?></a>
    </li>
</ul>