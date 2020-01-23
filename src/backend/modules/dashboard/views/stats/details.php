<?php

use common\helpers\Lang;
use common\helpers\Url;

/* @var $this \yii\web\View */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
?>
<div class="row">
    <div class="kt-portlet">
        <div class="col-md-12">
            <a href="<?= Url::to(['/conf/country-units/index']) ?>" class="btn btn-outline-secondary">
                <span class="kt-nav__link-text"><?= Lang::t('Farm Stats') ?></span>
            </a>
            <a href="<?= Url::to(['/conf/country-units/index']) ?>" class="btn btn-outline-secondary">
                <span class="kt-nav__link-text"><?= Lang::t('LSF Farm Stats') ?></span>
            </a>
            <a href="<?= Url::to(['/conf/country-units/index']) ?>" class="btn btn-outline-secondary">
                <span class="kt-nav__link-text"><?= Lang::t('Milk Report(Test Day)') ?></span>
            </a>
            <a href="<?= Url::to(['/conf/country-units/index']) ?>" class="btn btn-outline-secondary">
                <span class="kt-nav__link-text"><?= Lang::t('Genotype Animals By Region') ?></span>
            </a>
            <a href="<?= Url::to(['/conf/country-units/index']) ?>" class="btn btn-outline-secondary">
                <span class="kt-nav__link-text"><?= Lang::t('AI,PD & Calving') ?></span>
            </a>
        </div>
    </div>
</div>
