<?php

use backend\modules\core\models\OrganizationUnits;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\modules\core\models\Organization */
$tab = Yii::$app->request->get('level', 0);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= $tab == 0 ? ' active' : '' ?>"
           href="<?= Url::to(['organization/view', 'id' => $model->uuid]) ?>">
            <?= Lang::t('Country details') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == OrganizationUnits::LEVEL_REGION ? ' active' : '' ?>"
           href="<?= Url::to(['organization-units/index', 'level' => OrganizationUnits::LEVEL_REGION, 'org_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit1_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == OrganizationUnits::LEVEL_DISTRICT ? ' active' : '' ?>"
           href="<?= Url::to(['/core/organization-units/index', 'level' => OrganizationUnits::LEVEL_DISTRICT, 'org_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit2_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == OrganizationUnits::LEVEL_WARD ? ' active' : '' ?>"
           href="<?= Url::to(['/core/organization-units/index', 'level' => OrganizationUnits::LEVEL_WARD, 'org_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit3_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == OrganizationUnits::LEVEL_VILLAGE ? ' active' : '' ?>"
           href="<?= Url::to(['/core/organization-units/index', 'level' => OrganizationUnits::LEVEL_VILLAGE, 'org_id' => $model->uuid]) ?>">
            <?= Html::encode($model->unit4_name) ?>
        </a>
    </li>
</ul>