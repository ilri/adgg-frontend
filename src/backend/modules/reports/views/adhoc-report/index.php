<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\reports\models\AdhocReport */
/* @var $orgModel \backend\modules\core\models\Organization */

$this->title = \common\helpers\Lang::t('Ad-hoc Reports');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@reportsModule/views/layouts/_tab', ['orgModel' => $orgModel]) ?>
        <div class="tab-content">
            <?= $this->render('_filter', ['model' => $searchModel,  ]) ?>
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>