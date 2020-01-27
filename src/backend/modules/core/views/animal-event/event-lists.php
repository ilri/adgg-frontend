<?php

use backend\modules\core\models\Organization;
use common\helpers\Lang;
use common\helpers\Url;

/* @var $country Organization */
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
                    <div class="col-lg-4 col-xl-4 order-lg-2 order-xl-2">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <a href="<?= Url::to(['/core/animal-event/index', 'org_id' => $country->id, 'event_type' => $key]) ?>"
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