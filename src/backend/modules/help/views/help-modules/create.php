<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\help\models\HelpModules */

$this->title = 'Create Help Modules';
$this->params['breadcrumbs'][] = ['label' => 'Help Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-modules-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
