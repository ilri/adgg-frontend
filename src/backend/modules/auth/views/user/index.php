<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\auth\models\Users */
/* @var $countryModel \backend\modules\core\models\Country */

$this->title = \common\helpers\Lang::t('Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('@authModule/views/layouts/_tab', ['countryModel' => $countryModel]) ?>
        <div class="tab-content">
            <?= $this->render('_filter', ['model' => $searchModel,]) ?>
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>
