<?php

/* @var $this yii\web\View */

$this->title = 'Manage Android App Versions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('@app/modules/conf/views/layouts/_submenu'); ?>
    </div>
    <div class="col-md-10">
        <?= $this->render('@app/modules/conf/views/android-app/tab') ?>
        <div class="tab-content padding-top-10">
            <?= $this->render('_grid', ['model' => $searchModel]) ?>
        </div>
    </div>
</div>