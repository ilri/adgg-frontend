<?php

use backend\modules\help\Help;
use common\helpers\Url;
use yii\helpers\Html;

/* @var $contents \backend\modules\help\models\HelpContent[] */
?>

<div class="row">
    <div class="col-md-12">
        <p>Results Returned: <?= count($contents) ?></p>
        <?php if (!empty($contents)): ?>
            <?php foreach ($contents as $content): ?>
                <a href="#<?= $content->slug ?>" data-toggle="collapse" class="help-topic-referenced-section">
                    <h2>
                        <?= Html::encode(ucfirst($content->name)) ?>
                    </h2>
                </a>
                <div id="<?= $content->slug ?>" class="panel collapse">
                    <p class="text-muted">Related Module:<a href="<?= Url::to([
                            'content',
                            'module' => Help::isDefault($content->module) ? Help::DEFAULT_MODULE : $content->module->name,
                            'action' => Help::isDefault($content->module) ? 0 : Help::VIEW
                        ]) ?>"><?= Html::encode($content->module->name) ?></a>
                    </p>
                    <?= $content['content'] ?>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="alert alert-info">
                <p>No help content was found matching your query</p>
            </div>
        <?php endif; ?>
    </div>
</div>
