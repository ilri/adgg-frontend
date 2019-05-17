<?php

use backend\modules\help\models\HelpContent;


/* @var $this yii\web\View */
/* @var $model HelpContent */

$this->title = 'Create Help Content';
$this->params['breadcrumbs'][] = ['label' => 'Help Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('@helpModule/views/layouts/submenu'); ?>
    </div>
    <div class="col-md-10">
        <?= $this->render('_tab'); ?>
        <div class="tab-content padding-top-10">
            <div class="well">
                <?= $this->render('_create', [
                    'model' => $model,
                ]) ?>
            </div>

        </div>
    </div>
</div>
