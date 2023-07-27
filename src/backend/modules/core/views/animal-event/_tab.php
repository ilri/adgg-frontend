<?php

use backend\controllers\BackendController;
use backend\modules\auth\Session;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CountriesDashboardStats;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

/* @var $controller BackendController */
/* @var $model AnimalEvent */
/* @var $country \backend\modules\core\models\Country */
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
        } elseif ($key == AnimalEvent::EVENT_TYPE_FEEDING) {
            $url = 'feeding-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_VACCINATION) {
            $url = 'vaccination-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_PARASITE_INFECTION) {
            $url = 'parasite-infection-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_INJURY) {
            $url = 'injury-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_INJURY) {
            $url = 'injury-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_HOOF_HEALTH) {
            $url = 'hoof-health-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_HOOF_TREATMENT) {
            $url = 'hoof-treatment-event/index';
        } elseif ($key == AnimalEvent::EVENT_TYPE_EXITS) {
            $url = 'exit-event/index';
        }elseif ($key == AnimalEvent::EVENT_TYPE_SAMPLING) {
            $url = 'sampling-event/index';
        }elseif ($key == AnimalEvent::EVENT_TYPE_STRAW) {
            $url = 'straw-event/index';
        }

        ?>
        <li class="nav-item">
            <a class="nav-link"
               href="<?= Url::to([$url, 'country_id' => (!empty($country) ? $country->id : null), 'event_type' => $key]) ?>">
                <?= Lang::t('{name}', ['name' => $name]); ?>
                <span class="badge badge-secondary badge-pill">
                    <?= Yii::$app ->formatter->asDecimal(CountriesDashboardStats::getEventCounts((!empty($country) ? $country->id : null), $key)) ?>
        </span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>