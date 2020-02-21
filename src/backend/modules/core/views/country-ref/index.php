<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\core\models\CountryRef */
$this->title = 'Master Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-2">
        <?= $this->render('@confModule/views/layouts/_submenu'); ?>
    </div>
    <div class="col-lg-10">
        <?= $this->render('@coreModule/views/layouts/_masterDataSubmenu'); ?>
        <div class="tab-content">
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>