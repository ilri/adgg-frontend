<?php

/* @var $this yii\web\View */
/* @var $searchModel \backend\modules\auth\models\Roles */
/* @var $countryModel \backend\modules\core\models\Country */

$this->title = \common\helpers\Lang::t('Roles & Privileges');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <?= $this->render('@authModule/views/layouts/_tab', ['countryModel' => $countryModel]) ?>
        <div class="tab-content">
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>
