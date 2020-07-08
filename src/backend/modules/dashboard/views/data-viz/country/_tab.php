<?php

use common\helpers\Lang;
use yii\helpers\Url;

/* @var $controller backend\controllers\BackendController */
$controller = Yii::$app->controller;
$tabType = Yii::$app->request->get('tab_type', null);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" href="#" data-toggle="tab" data-target="#performance">
            <?= Lang::t('Performance') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="tab" data-target="#insemination">
            <?= Lang::t('Insemination') ?>
        </a>
    </li>
    <!--<li class="nav-item">
        <a class="nav-link<?= $tabType == 'insemination' ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'tab_type' => null, 'country_id' => !empty($country) ? $country->id : null]) ?>">
            <?= Lang::t('Insemination') ?>
        </a>
    </li>-->
</ul>