<?php

use backend\controllers\BackendController;
use backend\modules\core\models\Client;
use backend\modules\core\models\Country;
use common\helpers\Lang;
use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $searchModel Client */
/* @var $controller BackendController */
/* @var $countryModel Country */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'][] = ['label' => Lang::t('Countries'), 'url' => ['organization-ref/index']];
$this->params['breadcrumbs'][] = ['label' => \common\helpers\Lang::t('Countries'), 'url' => ['organization-ref/index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($countryModel->name), 'url' => ['organization-ref/view', 'id' => $countryModel->uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@coreModule/views/organization-ref/_tab', ['model' => $countryModel]); ?>
        <div class="tab-content">
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>
