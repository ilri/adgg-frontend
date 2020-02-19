<?php

use backend\modules\core\models\CountryUnits;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\modules\core\models\Country */
$tab = Yii::$app->request->get('level', 0);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= $tab == 0 ? ' active' : '' ?>"
           href="<?= Url::to(['organization-ref/view', 'id' => $model->uuid]) ?>">
            <?= Lang::t('Country details') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == CountryUnits::LEVEL_REGION ? ' active' : '' ?>"
           href="<?= Url::to(['organization-ref-units/index', 'level' => CountryUnits::LEVEL_REGION, 'country_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit1_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == CountryUnits::LEVEL_DISTRICT ? ' active' : '' ?>"
           href="<?= Url::to(['/core/organization-ref-units/index', 'level' => CountryUnits::LEVEL_DISTRICT, 'country_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit2_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == CountryUnits::LEVEL_WARD ? ' active' : '' ?>"
           href="<?= Url::to(['/core/organization-ref-units/index', 'level' => CountryUnits::LEVEL_WARD, 'country_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit3_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == CountryUnits::LEVEL_VILLAGE ? ' active' : '' ?>"
           href="<?= Url::to(['/core/organization-ref-units/index', 'level' => CountryUnits::LEVEL_VILLAGE, 'country_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit4_name) ?>
        </a>
    </li>
</ul>