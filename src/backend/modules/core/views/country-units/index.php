<?php

use common\helpers\Lang;
use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $searchModel \backend\modules\core\models\CountryUnits */
/* @var $controller \backend\controllers\BackendController */
/* @var $countryModel \backend\modules\core\models\Country */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'][] = ['label' => Lang::t('Countries'), 'url' => ['country/index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($countryModel->name), 'url' => ['country/view', 'id' => $countryModel->uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@coreModule/views/country/_tab', ['model' => $countryModel]); ?>
        <div class="tab-content">
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>
