<?php

use yii\helpers\Json;


/* @var $this yii\web\View */
/* @var $searchModel backend\modules\reports\models\AdhocReport */
/* @var $countryModel \backend\modules\core\models\Country */

$this->title = \common\helpers\Lang::t('Ad-hoc Reports');
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