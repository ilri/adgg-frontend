<?php

use backend\controllers\BackendController;
use backend\modules\core\models\Country;
use backend\modules\core\models\FarmMetadataType;
use common\helpers\Lang;

/* @var $this \yii\web\View */
/* @var $searchModel FarmMetadataType */
/* @var $countryModel Country */
/* @var $controller BackendController */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'][] = ['label' => Lang::t('Countries'), 'url' => ['country/index']];
$this->params['breadcrumbs'][] = $this->title;

$metadataTab = Yii::$app->request->get('metadataTab');
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@coreModule/views/country/_tab', ['model' => $countryModel]); ?>
        <div class="tab-content">
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>
