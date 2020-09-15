<?php

use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use backend\modules\dashboard\models\DataViz;
use common\helpers\Lang;
use common\helpers\Url;
use common\widgets\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $controller backend\controllers\BackendController */
/* @var $filterOptions array */

$controller = Yii::$app->controller;
$tabType = Yii::$app->request->get('tab_type', null);
?>
<div class="mb-3">
    <div class="card card-body">
        <div class="col-md-12" id="summaries_boxes"></div>
    </div>
</div>

<div class="mb-3">
    <div class="card card-body">
        <div class="col-md-12" id="summaries_breeds_by_region"></div>
    </div>
</div>

<div class="mb-3">
    <div class="card card-body">
        <div class="col-md-12" id="summaries_categories_by_region"></div>
    </div>
</div>