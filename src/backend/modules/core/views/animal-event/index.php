<?php

use common\helpers\Lang;

/* @var $this \yii\web\View */
/* @var $searchModel \backend\modules\core\models\AnimalEvent */
/* @var $controller \backend\controllers\BackendController */
/* @var $grid string */
/* @var $upload_url string */

$controller = Yii::$app->controller;

$this->title = Lang::t('{resource} data', ['resource' => $controller->resourceLabel]);
$this->params['breadcrumbs'] = [
    $this->title
];

?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@coreModule/views/animal-event/_tab', ['model' => $searchModel]) ?>
        <div class="tab-content">
            <?= $this->render('_filter', ['model' => $searchModel,]) ?>
            <?= $this->render('grids/' . $grid, ['model' => $searchModel, 'upload_url' => $upload_url]) ?>
        </div>
    </div>
</div>