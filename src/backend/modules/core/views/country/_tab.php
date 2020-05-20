<?php

use backend\modules\auth\Session;
use backend\modules\core\models\CountryUnits;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\modules\core\models\Country */
$tab = Yii::$app->request->get('level', 0);
$orgTab = Yii::$app->request->get('orgTab');
$clientTab = Yii::$app->request->get('clientTab');
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= ($tab==0 && $orgTab==null && $clientTab==null) ? ' active' : '' ?>"
           href="<?= Url::to(['country/view', 'id' => $model->uuid]) ?>">
            <?= Lang::t('Country details') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == CountryUnits::LEVEL_REGION ? ' active' : '' ?>"
           href="<?= Url::to(['country-units/index', 'level' => CountryUnits::LEVEL_REGION, 'country_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit1_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == CountryUnits::LEVEL_DISTRICT ? ' active' : '' ?>"
           href="<?= Url::to(['/core/country-units/index', 'level' => CountryUnits::LEVEL_DISTRICT, 'country_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit2_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == CountryUnits::LEVEL_WARD ? ' active' : '' ?>"
           href="<?= Url::to(['/core/country-units/index', 'level' => CountryUnits::LEVEL_WARD, 'country_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit3_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == CountryUnits::LEVEL_VILLAGE ? ' active' : '' ?>"
           href="<?= Url::to(['/core/country-units/index', 'level' => CountryUnits::LEVEL_VILLAGE, 'country_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit4_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($tab==0 && $orgTab==true) ? ' active' : '' ?>"
           href="<?= Url::to(['/core/organization/index', 'country_id' => $model->id,'orgTab'=>true]) ?>">
            <?= Lang::t('Organization') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($tab==0 && $clientTab==true) ? ' active' : '' ?>"
           href="<?= Url::to(['/core/client/index', 'country_id' => $model->id,'clientTab'=>true]) ?>">
            <?= Lang::t('Clients') ?>
        </a>
    </li>
</ul>