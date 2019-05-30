<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\core\models\RegistrationDocumentType */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = $controller->getPageTitle();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-2">
        <?= $this->render('@confModule/views/layouts/_submenu'); ?>
    </div>
    <div class="col-lg-10">
        <?= $this->render('@coreModule/views/layouts/_registrationTab'); ?>
        <div class="tab-content">
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>