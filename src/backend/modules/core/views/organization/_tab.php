<?php

use backend\modules\core\models\OrganizationUnits;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\modules\core\models\Organization */
$tab = Yii::$app->request->get('tab', 1);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= $tab == 1 ? ' active' : '' ?>"
           href="<?= Url::to(['organization/view', 'id' => $model->uuid, 'tab' => 1]) ?>">
            <?= Lang::t('Country details') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == 2 ? ' active' : '' ?>"
           href="<?= Url::to(['organization-units/index', 'level' => OrganizationUnits::LEVEL_UNIT_1, 'org_id' => $model->uuid, 'tab' => 2]) ?>">
            <?= Html::encode($model->unit1_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == 3 ? ' active' : '' ?>"
           href="<?= Url::to(['/core/organization-units/index', 'level' => OrganizationUnits::LEVEL_UNIT_2, 'org_id' => $model->uuid, 'tab' => 3]) ?>">
            <?= Html::encode($model->unit2_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == 4 ? ' active' : '' ?>"
           href="<?= Url::to(['/core/organization-units/index', 'level' => OrganizationUnits::LEVEL_UNIT_3, 'org_id' => $model->uuid, 'tab' => 4]) ?>">
            <?= Html::encode($model->unit3_name) ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == 5 ? ' active' : '' ?>"
           href="<?= Url::to(['/core/organization-units/index', 'level' => OrganizationUnits::LEVEL_UNIT_4, 'org_id' => $model->uuid, 'tab' => 5]) ?>">
            <?= Html::encode($model->unit4_name) ?>
        </a>
    </li>
</ul>