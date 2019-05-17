<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\help\models\HelpModules */

$this->title = 'Update Help Modules: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Help Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="help-modules-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
