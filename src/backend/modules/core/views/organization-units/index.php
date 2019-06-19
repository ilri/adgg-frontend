<?php

use common\helpers\Lang;
use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $searchModel \backend\modules\core\models\OrganizationUnits */
/* @var $controller \backend\controllers\BackendController */
/* @var $orgModel \backend\modules\core\models\Organization */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'][] = ['label' => Lang::t('Countries'), 'url' => ['organization/index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($orgModel->name), 'url' => ['organization/view', 'id' => $orgModel->uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@coreModule/views/organization/_tab', ['model' => $orgModel]); ?>
        <div class="tab-content">
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>
