<?php

use common\helpers\Lang;
use yii\helpers\Url;

?>
<ul class="nav nav-tabs my-nav" role="tablist">
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['/core/country/index']) ?>"><?= Lang::t('Countries') ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['/core/county/index']) ?>"><?= Lang::t('Counties') ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= Url::to(['/core/currency/index']) ?>"><?= Lang::t('Currencies') ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           href="<?= Url::to(['/core/currency-conversion/update']) ?>"><?= Lang::t('Currency Conversion Rates') ?></a>
    </li>
</ul>