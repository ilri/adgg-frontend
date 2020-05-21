<?php

use backend\modules\auth\Session;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Farm;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Choices;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

/* @var $controller backend\controllers\BackendController */
/* @var $model Farm */
/* @var $country \backend\modules\core\models\Country */
$controller = Yii::$app->controller;
$farmType = Yii::$app->request->get('farm_type', null);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= empty($farmType) ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'farm_type' => null, 'country_id' => !empty($country) ? $country->id : null]) ?>">
            <?= Lang::t('All Farms') ?>
            <span class="badge badge-secondary badge-pill">
                <?= CountriesDashboardStats::getFarmCounts((!empty($country) ? $country->id : null), false, null, null) ?>

            </span>
        </a>
    </li>
    <?php foreach (Choices::getList(ChoiceTypes::CHOICE_TYPE_FARM_TYPE, false) as $value => $label): ?>
        <li class="nav-item">
            <a class="nav-link<?= ($farmType == $value) ? ' active' : '' ?>"
               href="<?= Url::to(['index', 'farm_type' => $value, 'country_id' => !empty($country) ? $country->id : null]) ?>">
                <?= strtoupper(Html::encode($label)) ?>
                <span class="badge badge-secondary badge-pill">
                    <?= CountriesDashboardStats::getFarmCounts((!empty($country) ? $country->id : null), false, null, $value) ?>
                </span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>