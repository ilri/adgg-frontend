<?php

use common\helpers\Lang;

/* @var $this yii\web\View */
/* @var $model backend\modules\auth\models\Users */

$this->title = Lang::t('Change your password');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_profileHeader', ['model' => $model]) ?>
<?= $this->render('_changePasswordForm', ['model' => $model]) ?>