<?php

/* @var $this \yii\web\View */
/* @var $searchModel \backend\modules\core\models\AnimalHerd */
/* @var $controller \backend\controllers\BackendController */
/* @var $country_id int*/

use backend\modules\core\models\Country;

$controller = Yii::$app->controller;

$this->title = $controller->getPageTitle();
if ($country_id) {
    $this->params['breadcrumbs'][] = Country::getScalar('name', ['id' => $country_id]);
}
$this->params['breadcrumbs'] [] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('_filter', ['model' => $searchModel,]) ?>
        <?= $this->render('_grid', ['model' => $searchModel]) ?>
    </div>
</div>