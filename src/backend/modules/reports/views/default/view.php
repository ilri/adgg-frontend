<?php

/* @var $this yii\web\View */

use backend\modules\core\Constants;
use backend\modules\core\models\Country;
use backend\modules\reports\models\Reports;
use common\helpers\Lang;
use common\helpers\Url;
use yii\bootstrap\Html;

/* @var $type int */
/* @var $tpl string */
/* @var $country_id int */
/* @var $searchModel \backend\modules\reports\models\AdhocReport */
/* @var $controller \backend\controllers\BackendController */
/* @var $filterOptions array */

$this->title = 'Standard Extracts';
$this->params['breadcrumbs'][] = $this->title;
if($country_id) {
    $this->params['breadcrumbs'][] = Country::getScalar('name', ['id' => $country_id]);
}

?>
<?php if (Yii::$app->user->canView(Constants::RES_REPORT_BUILDER)): ?>
<div class="row">
    <div class="col-md-12">
        <?= $this->render('reports/' . $tpl, ['country_id' => $country_id, 'type' => $type, 'filterOptions' => $filterOptions]) ?>
    </div>
    <div class="col-md-12 well mt-3">
        <?= $this->render('/adhoc-report/_grid', ['model' => $searchModel]) ?>
    </div>
</div>
<?php endif; ?>