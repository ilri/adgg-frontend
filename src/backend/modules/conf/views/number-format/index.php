<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\conf\models\NumberingFormat */

$this->title = 'Numbering Format';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-2">
        <?= $this->render('@app/modules/conf/views/layouts/_submenu'); ?>
    </div>
    <div class="col-lg-10">
        <?= $this->render('_grid', ['model' => $searchModel]) ?>
    </div>
</div>