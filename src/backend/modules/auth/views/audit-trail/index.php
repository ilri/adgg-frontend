<?php

$this->title = \common\helpers\Lang::t('Audit Trail');
$this->params['breadcrumbs'][] = $this->title;

/* @var $searchModel \backend\modules\auth\models\AuditTrail */
/* @var $this yii\web\View */
/* @var $countryModel \backend\modules\core\models\Country */
?>
<div class="row">
    <div class="col-md-12">
        <?= $this->render('@authModule/views/layouts/_tab', ['countryModel' => $countryModel]) ?>
        <div class="tab-content">
            <?= $this->render('_filter', ['model' => $searchModel]); ?>
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>

