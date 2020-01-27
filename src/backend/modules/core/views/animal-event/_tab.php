<?php

use backend\controllers\BackendController;
use backend\modules\core\models\AnimalEvent;
use common\helpers\Lang;
use yii\helpers\Url;

/* @var $controller BackendController */
/* @var $model AnimalEvent */
$controller = Yii::$app->controller;
?>
<ul class="nav nav-tabs my-nav" role="tablist">
    <li class="nav-item">
        <a class="nav-link"
           href="<?= Url::to(['/core/calving-event/index']) ?>">
            <?= Lang::t('Calving') ?>
            <span class="badge badge-important badge-pill">
                (<?= number_format(AnimalEvent::getCount(['event_type' => AnimalEvent::EVENT_TYPE_CALVING])) ?>)
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           href="<?= Url::to(['/core/milking-event/index']) ?>">
            <?= Lang::t('Milking') ?>
            <span class="badge badge-important badge-pill">
                (<?= number_format(AnimalEvent::getCount(['event_type' => AnimalEvent::EVENT_TYPE_MILKING])) ?>)
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           href="<?= Url::to(['/core/insemination-event/index']) ?>">
            <?= Lang::t('Insemination') ?>
            <span class="badge badge-important badge-pill">
                (<?= number_format(AnimalEvent::getCount(['event_type' => AnimalEvent::EVENT_TYPE_AI])) ?>)
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           href="<?= Url::to(['/core/pd-event/index']) ?>">
            <?= Lang::t('Pregnancy Diagnosis') ?>
            <span class="badge badge-important badge-pill">
                (<?= number_format(AnimalEvent::getCount(['event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS])) ?>)
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           href="<?= Url::to(['/core/synchronization-event/index']) ?>">
            <?= Lang::t('Synchronization') ?>
            <span class="badge badge-important badge-pill">
                (<?= number_format(AnimalEvent::getCount(['event_type' => AnimalEvent::EVENT_TYPE_SYNCHRONIZATION])) ?>)
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           href="<?= Url::to(['/core/weight-event/index']) ?>">
            <?= Lang::t('Weights') ?>
            <span class="badge badge-important badge-pill">
                (<?= number_format(AnimalEvent::getCount(['event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS])) ?>)
            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           href="<?= Url::to(['/core/health-event/index']) ?>">
            <?= Lang::t('Health') ?>
            <span class="badge badge-important badge-pill">
                (<?= number_format(AnimalEvent::getCount(['event_type' => AnimalEvent::EVENT_TYPE_HEALTH])) ?>)
            </span>
        </a>
    </li>
    <li class="nav-item hidden">
        <a class="nav-link"
           href="<?= Url::to(['#']) ?>">
            <?= Lang::t('Feeding') ?>
            <span class="badge badge-important badge-pill">
                (<?= number_format(AnimalEvent::getCount(['event_type' => AnimalEvent::EVENT_TYPE_FEEDING])) ?>)
            </span>
        </a>
    </li>
    <li class="nav-item hidden">
        <a class="nav-link"
           href="<?= Url::to(['#']) ?>">
            <?= Lang::t('Exits') ?>
            <span class="badge badge-important badge-pill">
                (<?= number_format(AnimalEvent::getCount(['event_type' => AnimalEvent::EVENT_TYPE_EXITS])) ?>)
            </span>
        </a>
    </li>
</ul>