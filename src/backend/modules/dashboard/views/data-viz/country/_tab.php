<?php

use common\helpers\Lang;
use yii\helpers\Url;

/* @var $controller backend\controllers\BackendController */
$controller = Yii::$app->controller;
$tabType = Yii::$app->request->get('tab_type', null);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="tab" data-target="#summaries">
            <?= Lang::t('Summaries') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="#" data-toggle="tab" data-target="#performance">
            <?= Lang::t('Animal Performance') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="tab" data-target="#service">
            <?= Lang::t('Animal Service') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="tab" data-target="#genetic">
            <?= Lang::t('Animal Genetic Ranking') ?>
        </a>
    </li>
    <!--<li class="nav-item">
        <a class="nav-link<?= $tabType == 'insemination' ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'tab_type' => null, 'country_id' => !empty($country) ? $country->id : null]) ?>">
            <?= Lang::t('Insemination') ?>
        </a>
    </li>-->
</ul>