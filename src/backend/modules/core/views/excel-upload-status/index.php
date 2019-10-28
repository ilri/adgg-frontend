<?php
use common\helpers\Lang;
/* @var $this \yii\web\View */
/* @var $searchModel \backend\modules\core\models\ExcelImport */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;

$this->title = Lang::t('{resource}', ['resource' => $controller->resourceLabel]);
$this->params['breadcrumbs'] = [
    $this->title
];
 ?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('_grid', ['model' => $searchModel]) ?>
    </div>
</div>