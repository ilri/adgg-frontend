<?php

use common\helpers\Lang;

/* @var $this \yii\web\View */
/* @var $searchModel \backend\modules\core\models\AnimalEvent */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;

$this->title = Lang::t('{resource} data', ['resource' => $controller->resourceLabel]);
$this->params['breadcrumbs'] = [
    $this->title
];

?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('_filter', ['model' => $searchModel,]) ?>
        <?= $this->render('_grid', ['model' => $searchModel]) ?>
    </div>
</div>