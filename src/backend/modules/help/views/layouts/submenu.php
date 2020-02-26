<?php
/* @var $this yii\web\View */
use common\helpers\Lang;
use common\helpers\Url;

?>
<div class="list-group my-list-group">
    <a href="<?= Url::to(['/help/help-content/index']) ?>" class="list-group-item">
        <i class="fa fa-backward"></i>&nbsp;<?= Lang::t('Back') ?>
    </a>
</div>
<hr>
<div class="list-group my-list-group">
    <a href="<?= Url::to(['/help/help-modules/index']) ?>" class="list-group-item">
        <?= Lang::t('Help Modules') ?>
    </a>
    <a href="<?= Url::to(['/help/help-content/index']) ?>" class="list-group-item">
        <?= Lang::t('Help Content') ?>
    </a>
</div>
