<?php
use common\helpers\Lang;

/* @var $this yii\web\View */
/* @var $model backend\modules\conf\models\EmailTemplate */

$this->title = Lang::t('Add Email Template');
$this->params['breadcrumbs'] = [
    ['label' => 'Email Templates', 'url' => ['index']],
    $this->title
];
?>
<div class="row">
    <div class="col-lg-2">
        <?= $this->render('@app/modules/conf/views/layouts/_submenu'); ?>
    </div>
    <div class="col-lg-10">
        <?= $this->render('_form', ['model' => $model]); ?>
    </div>
</div>