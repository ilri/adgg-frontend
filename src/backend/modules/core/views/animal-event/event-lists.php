<?php

use backend\modules\auth\Session;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use common\helpers\Lang;
use common\helpers\Url;
use yii\helpers\Html;

/* @var $country Country */
/* @var $events */
$this->title = 'Events';
if ($country) {
    $this->params['breadcrumbs'][] = Country::getScalar('name', ['id' => $country]);
}
$this->params['breadcrumbs'] [] = $this->title;
?>
<div class="row">
    <div class="col-md-12" title="Click to view details">
        <div class="well">
            <h3>
                <?php if (Session::isVillageUser()): ?>
                    <?= Lang::t('List of Animal Events in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getVillageId(), 'level' => CountryUnits::LEVEL_VILLAGE]) . ' ' . 'Village' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                <?php elseif (Session::isWardUser()): ?>
                    <?= Lang::t('List of Animal Events in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getWardId(), 'level' => CountryUnits::LEVEL_WARD]) . ' ' . 'Ward' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                <?php elseif (Session::isDistrictUser()): ?>
                    <?= Lang::t('List of Animal Events in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]) . ' ' . 'District' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                <?php elseif (Session::isRegionUser()): ?>
                    <?= Lang::t('List of Animal Events in') . ' ' . CountryUnits::getScalar('name', ['id' => Session::getRegionId(), 'level' => CountryUnits::LEVEL_REGION]) . ' ' . 'Region' . ' ' . '[' . Html::encode($country->name) . ']'; ?>
                <?php elseif(empty($country)): ?>
                    <?= Lang::t('List of Animal Events for all Countries')?>
                    <?php else: ?>
                    <?= Lang::t('List of Animal Events in {country}', ['country' => $country->name]) ?>
                <?php endif; ?>
            </h3>
            <hr>
            <div class="row">
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
                    } elseif ($key == AnimalEvent::EVENT_TYPE_HOOF_HEALTH) {
                        $url = 'hoof-health-event/index';
                    } elseif ($key == AnimalEvent::EVENT_TYPE_HEALTH) {
                        $url = 'health-event/index';
                    } elseif ($key == AnimalEvent::EVENT_TYPE_EXITS) {
                        $url = 'exit-event/index';
                    }
                    ?>
                    <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <a href="<?= Url::to([$url, 'country_id' => !empty($country) ? $country->id : null, 'event_type' => $key]) ?>"
                               class="kt-iconbox kt-iconbox--active">
                                <div class="kt-iconbox__title">
                                    <?= Lang::t('{name}', ['name' => $name]); ?>
                                </div>
                            </a>
                        </div>
                        <!--end::Portlet-->
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>