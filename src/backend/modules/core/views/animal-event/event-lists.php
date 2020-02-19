<?php

use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\OrganizationRef;
use common\helpers\Lang;
use common\helpers\Url;

/* @var $country OrganizationRef */
/* @var $events */
$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12" title="Click to view details">
        <div class="well">
            <h3><?= Lang::t('List Of Animal  Events In {country}', ['country' => $country->name]) ?></h3>
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
                    } elseif ($key == AnimalEvent::EVENT_TYPE_HEALTH) {
                        $url = 'health-event/index';
                    } elseif ($key == AnimalEvent::EVENT_TYPE_FEEDING) {
                        $url = 'feeding-event/index';
                    } elseif ($key == AnimalEvent::EVENT_TYPE_EXITS) {
                        $url = 'exit-event/index';
                    }
                    ?>
                    <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <a href="<?= Url::to([$url, 'country_id' => $country->id, 'event_type' => $key]) ?>"
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