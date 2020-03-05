<?php

use backend\controllers\BackendController;
use backend\modules\auth\Session;
use backend\modules\core\models\AnimalEvent;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

/* @var $controller BackendController */
/* @var $model AnimalEvent */
/* @var $events */
$controller = Yii::$app->controller;
$events = AnimalEvent::eventTypeOptions();
?>
<ul class="nav nav-tabs my-nav" role="tablist">
    <?php foreach ($events as $key => $name): ?>
        <?php
        $url = null;
        if ($key == AnimalEvent::EVENT_TYPE_CALVING) {
            $url = 'calving-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_MILKING) {
            $url = 'milking-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_AI) {
            $url = 'insemination-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS) {
            $url = 'pd-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_SYNCHRONIZATION) {
            $url = 'synchronization-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_WEIGHTS) {
            $url = 'weight-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_HEALTH) {
            $url = 'health-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_FEEDING) {
            $url = 'feeding-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_EXITS) {
            $url = 'exit-event/index';
        }
        ?>
    <li class="nav-item">
        <a class="nav-link"
            href="<?= Url::to([$url, 'country_id' => Session::getCountryId(), 'event_type' => $key]) ?>">
            <?= Lang::t('{name}', ['name' => $name]); ?>
        <span class="badge badge-secondary badge-pill">
            <?php if (Session::isVillageUser()): ?>
                <?= Yii::$app->formatter->asDecimal(AnimalEvent::find()->andFilterWhere(['country_id' => $country->id, 'village_id' => Session::getVillageId(), 'event_type' => $key, 'field_agent_id' => Session::getUserId()])->count()) ?>
            <?php elseif (Session::isWardUser()): ?>
                <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'ward_id' => Session::getWardId(), 'event_type' => $key])) ?>
            <?php elseif (Session::isDistrictUser()): ?>
                <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'district_id' => Session::getDistrictId(), 'event_type' => $key])) ?>
            <?php elseif (Session::isRegionUser()): ?>
                <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'region_id' => Session::getRegionId(), 'event_type' => $key])) ?>
            <?php else: ?>
                <?= Yii::$app->formatter->asDecimal(AnimalEvent::getCount(['country_id' => $country->id, 'event_type' => $key])) ?>
            <?php endif; ?>
        </span>
        </a>
        </li>
    <?php endforeach; ?>
</ul>