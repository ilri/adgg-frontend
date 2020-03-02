<?php

use backend\modules\auth\Session;
use backend\modules\core\models\Farm;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Choices;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

/* @var $controller backend\controllers\BackendController */
/* @var $model Farm */
$controller = Yii::$app->controller;
$farmType = Yii::$app->request->get('farm_type', null);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= empty($farmType) ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'farm_type' => null]) ?>">
            <?= Lang::t('All Farms') ?>
            <span class="badge badge-secondary badge-pill">
                <?php if (Session::isVillageUser()): ?>
                    <?= Yii::$app->formatter->asDecimal(Farm::find()->andFilterWhere(['country_id' => Session::getCountryId(), 'village_id' => Session::getVillageId()])->count()) ?>
                <?php elseif (Session::isWardUser()): ?>
                    <?= Yii::$app->formatter->asDecimal(Farm::getCount(['country_id' => Session::getCountryId(), 'ward_id' => Session::getWardId()])) ?>
                <?php elseif (Session::isDistrictUser()): ?>
                    <?= Yii::$app->formatter->asDecimal(Farm::getCount(['country_id' => Session::getCountryId(), 'district_id' => Session::getDistrictId()])) ?>
                <?php elseif (Session::isRegionUser()): ?>
                    <?= Yii::$app->formatter->asDecimal(Farm::getCount(['country_id' => Session::getCountryId(), 'region_id' => Session::getRegionId()])) ?>
                <?php else: ?>
                    <?= Yii::$app->formatter->asDecimal(Farm::getCount(['country_id' => Session::getCountryId()])) ?>
                <?php endif; ?>
            </span>
        </a>
    </li>
    <?php foreach (Choices::getList(ChoiceTypes::CHOICE_TYPE_FARM_TYPE, false) as $value => $label): ?>
        <li class="nav-item">
            <a class="nav-link<?= ($farmType == $value) ? ' active' : '' ?>"
               href="<?= Url::to(['index', 'farm_type' => $value]) ?>">
                <?= strtoupper(Html::encode($label)) ?>
                <span class="badge badge-secondary badge-pill">
                     <?php if (Session::isVillageUser()): ?>
                         <?= Yii::$app->formatter->asDecimal(Farm::find()->andFilterWhere(['country_id' => Session::getCountryId(), 'village_id' => Session::getVillageId(), 'farm_type' => $value])->count()) ?>
                     <?php elseif (Session::isWardUser()): ?>
                         <?= Yii::$app->formatter->asDecimal(Farm::getCount(['country_id' => Session::getCountryId(), 'ward_id' => Session::getWardId(), 'farm_type' => $value])) ?>
                     <?php elseif (Session::isDistrictUser()): ?>
                         <?= Yii::$app->formatter->asDecimal(Farm::getCount(['country_id' => Session::getCountryId(), 'district_id' => Session::getDistrictId(), 'farm_type' => $value])) ?>
                     <?php elseif (Session::isRegionUser()): ?>
                         <?= Yii::$app->formatter->asDecimal(Farm::getCount(['country_id' => Session::getCountryId(), 'region_id' => Session::getRegionId(), 'farm_type' => $value])) ?>
                     <?php else: ?>
                         <?= Yii::$app->formatter->asDecimal(Farm::getCount(['country_id' => Session::getCountryId(), 'farm_type' => $value])) ?>
                     <?php endif; ?>
                </span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>