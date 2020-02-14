<?php

use backend\modules\help\models\HelpModules;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model HelpModules */

?>
<div class="help-modules-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
