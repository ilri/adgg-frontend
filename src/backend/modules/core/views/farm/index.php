<?php

use backend\modules\auth\Session;
use backend\modules\core\models\Country;
use backend\modules\core\models\Farm;
use backend\modules\core\models\CountryUnits;

/* @var $this \yii\web\View */
/* @var $searchModel Farm */
/* @var $country \backend\modules\core\models\Country */
/* @var $controller \backend\controllers\BackendController */
/* @var $district \backend\modules\core\models\CountryUnits*/


$controller = Yii::$app->controller;

$this->title = $controller->getPageTitle();
//if ($country) {
//    $this->params['breadcrumbs'][] = Country::getScalar('name', ['id' => $country]);
//}

 if ($district){
    $this->params['breadcrumbs'][] = CountryUnits::getScalar('name', ['id' => Session::getDistrictId(), 'level' => CountryUnits::LEVEL_DISTRICT]);
}
$this->params['breadcrumbs'] [] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@coreModule/views/farm/_tab', ['model' => $searchModel, 'country' => !empty($country) ? $country : null]) ?>
        <div class="tab-content">
            <?= $this->render('_filter', ['model' => $searchModel,]) ?>
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>