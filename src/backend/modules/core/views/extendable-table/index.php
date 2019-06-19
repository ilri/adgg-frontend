<?php

use common\helpers\Lang;

/* @var $this \yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $groupSearchModel \backend\modules\core\models\TableAttributesGroup */
/* @var $searchModel \backend\modules\core\models\TableAttribute */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-2">
        <?= $this->render('@confModule/views/layouts/_submenu'); ?>
    </div>
    <div class="col-lg-10">
        <h2>
            <?= Lang::t('Extendable Tables') ?>
            <small>Set additional attributes</small>
        </h2>
        <?= $this->render('_tab'); ?>
        <div class="tab-content">
            <?= $this->render('@coreModule/views/table-attribute/_grid', ['model' => $searchModel]) ?>
            <?= $this->render('@coreModule/views/table-attributes-group/_grid', ['model' => $groupSearchModel]) ?>
        </div>
    </div>
</div>