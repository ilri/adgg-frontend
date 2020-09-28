<?php

use backend\modules\core\models\Country;
use yii\helpers\Json;


/* @var $this yii\web\View */
/* @var $searchModel backend\modules\reports\models\AdhocReport */
/* @var $countryModel \backend\modules\core\models\Country */
/* @var int $country_id */

$this->title = \common\helpers\Lang::t('Ad-hoc Reports');
if ($country_id) {
    $this->params['breadcrumbs'][] = Country::getScalar('name', ['id' => $country_id]);
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="tab-content">
            <?= $this->render('_filter', ['model' => $searchModel]) ?>
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>
<?php
$options = [];
$this->registerJs("MyApp.modules.reports.stdreport(" . Json::encode($options) . ");");
?>