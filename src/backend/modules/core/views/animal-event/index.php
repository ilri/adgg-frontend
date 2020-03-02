<?php

use backend\controllers\BackendController;
use backend\modules\core\models\AnimalEvent;
use common\helpers\Lang;
use yii\web\View;

/* @var $this View */
/* @var $searchModel AnimalEvent */
/* @var $controller BackendController */
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
        <?= $this->render('@coreModule/views/animal-event/_tab', ['model' => $searchModel, 'country' => $country]) ?>
        <div class="tab-content">
            <?= $this->render('_filter', ['model' => $searchModel,]) ?>
            <?= $this->render('grids/' . $grid, ['model' => $searchModel]) ?>
        </div>
    </div>
</div>