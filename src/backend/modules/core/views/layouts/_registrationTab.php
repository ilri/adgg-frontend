<?php

use common\helpers\Lang;
use yii\helpers\Url;

?>
<ul class="nav nav-tabs my-nav" role="tablist">
    <li class="nav-item">
        <a class="nav-link"
           href="<?= Url::to(['/core/registration-document-type/index']) ?>"><?= Lang::t('Registration Documents') ?></a>
    </li>
</ul>