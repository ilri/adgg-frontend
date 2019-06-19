<?php

use common\helpers\Lang;
use yii\helpers\Url;

?>
<ul class="nav nav-tabs my-nav" role="tablist">
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['/core/country/index']) ?>"><?= Lang::t('Countries') ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['/core/currency/index']) ?>"><?= Lang::t('Currencies') ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['/core/list-type/index']) ?>"><?= Lang::t('List Types') ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['/core/lookup-list/index']) ?>"><?= Lang::t('Lists') ?></a>
    </li>
</ul>