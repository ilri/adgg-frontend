<?php
/* @var $this yii\web\View */

use common\helpers\Lang;
use common\helpers\Url;

$case = Yii::$app->request->get('forAndroid');
if ($case == true) {
    $moduleUrl = ['/help/help-modules/index', 'forAndroid' => true];
    $contentUrl = ['/help/help-content/manual', 'forAndroid' => true];
} else {
    $moduleUrl = ['/help/help-modules/index'];
    $contentUrl = ['/help/help-content/manual'];
}
?>
<div class="list-group my-list-group">
    <a href="<?= Url::to(['/help/help-content/index']) ?>" class="list-group-item">
        <i class="fa fa-backward"></i>&nbsp;<?= Lang::t('Back') ?>
    </a>
</div>
<hr>
<div class="list-group my-list-group">
    <a href="<?= Url::to($moduleUrl) ?>" class="list-group-item">
        <?= Lang::t('Help Modules') ?>
    </a>
    <a href="<?= Url::to($contentUrl) ?>" class="list-group-item">
        <?= Lang::t('Help Content') ?>
    </a>
</div>
