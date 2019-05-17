<?php
/* @var $this yii\web\View */

use backend\modules\help\Help;
use backend\modules\help\models\HelpModules;
use common\helpers\Lang;

/* @var $allModules HelpModules[] */
/* @var $activeModule HelpModules */

?>

<div class="list-group">
    <a href="#" class="list-group-item disabled">
        <i class="fa fa-list"></i> <?= Lang::t('Help Modules') ?><span
                class="badge"><?= count($allModules) ?></span>
    </a>

    <?php foreach ($allModules as $module): ?>
        <?php if (Help::isDefault($module)): ?>
            <a class="<?= $module->name === $activeModule->name ? 'list-group-item active' : 'list-group-item' ?>"
               href="<?= Help::getContentUrl(null, ['module' => Help::DEFAULT_MODULE, 'action' => Help::VIEW]) ?>"
               class="list-group-item">
                <?= Lang::t(ucwords($module->name)) ?>
            </a>
        <?php else: ?>
            <a class="<?= $module->name === $activeModule->name ? 'list-group-item active' : 'list-group-item' ?>"
               href="<?= Help::getContentUrl(null, ['module' => $module->name, 'action' => Help::VIEW]) ?>"
               class="list-group-item">
                <?= Lang::t(ucwords($module->name)) ?>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
</div>