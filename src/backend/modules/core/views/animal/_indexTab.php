<?php

use backend\modules\auth\Session;
use backend\modules\core\models\Animal;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Choices;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

/* @var $controller \backend\controllers\BackendController */
/* @var $country \backend\modules\core\models\Country */
/* @var $model Animal */
$controller = Yii::$app->controller;
$animalType = Yii::$app->request->get('animal_type', null);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= empty($animalType) ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'animal_type' => null]) ?>">
            <?= Lang::t('All Animals') ?>
            <span class="badge badge-secondary badge-pill">
                <?php if (Session::isVillageUser()): ?>
                    <?= Yii::$app->formatter->asDecimal(Animal::find()->andFilterWhere(['country_id' => Session::getCountryId(), 'village_id' => Session::getVillageId()])->count()) ?>
                <?php elseif (Session::isWardUser()): ?>
                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => Session::getCountryId(), 'ward_id' => Session::getWardId()])) ?>
                <?php elseif (Session::isDistrictUser()): ?>
                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => Session::getCountryId(), 'district_id' => Session::getDistrictId()])) ?>
                <?php elseif (Session::isRegionUser()): ?>
                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => Session::getCountryId(), 'region_id' => Session::getRegionId()])) ?>
                <?php else: ?>
                    <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id])) ?>
                <?php endif; ?>
            </span>
        </a>
    </li>
    <?php foreach (Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, false) as $value => $label): ?>
        <li class="nav-item">
            <a class="nav-link<?= $animalType == $value ? ' active' : '' ?>"
               href="<?= Url::to(['index', 'animal_type' => $value]) ?>">
                <?= strtoupper(Html::encode(Inflector::pluralize($label))) ?>
                <span class="badge badge-secondary badge-pill">
                     <?php if (Session::isVillageUser()): ?>
                         <?= Yii::$app->formatter->asDecimal(Animal::find()->andFilterWhere(['country_id' => Session::getCountryId(), 'village_id' => Session::getVillageId(), 'animal_type' => $value])->count()) ?>
                     <?php elseif (Session::isWardUser()): ?>
                         <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => Session::getCountryId(), 'ward_id' => Session::getWardId(), 'animal_type' => $value])) ?>
                     <?php elseif (Session::isDistrictUser()): ?>
                         <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => Session::getCountryId(), 'district_id' => Session::getDistrictId(), 'animal_type' => $value])) ?>
                     <?php elseif (Session::isRegionUser()): ?>
                         <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => Session::getCountryId(), 'region_id' => Session::getRegionId(), 'animal_type' => $value])) ?>
                     <?php else: ?>
                         <?= Yii::$app->formatter->asDecimal(Animal::getCount(['country_id' => $country->id, 'animal_type' => $value])) ?>
                     <?php endif; ?>
                </span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>