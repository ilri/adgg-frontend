<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\Farm;
use backend\modules\core\models\MilkingEvent;
use common\helpers\DateUtils;
use common\helpers\Lang;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];

?>

<div class="row">
    <?= $this->render('partials/summary', ['filterOptions' => []]) ?>
</div>
<br/>
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card card-body">
            <?= $this->render('partials/milk', ['filterOptions' => []]) ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-body">
            <?= $this->render('partials/calf', ['filterOptions' => []]) ?>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card card-body">
            <?= $this->render('partials/insemination', ['filterOptions' => []]) ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-body">
            <?= $this->render('partials/animals', ['filterOptions' => []]) ?>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card card-body">
            <?= $this->render('partials/table', ['filterOptions' => []]) ?>
        </div>
    </div>
</div>