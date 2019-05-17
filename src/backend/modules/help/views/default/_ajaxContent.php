<?php

use yii\helpers\Html;

/* @var $contents \backend\modules\help\models\HelpContent[] */
?>
<div class="row">
    <div class="col-md-12">
        <?php if (!empty($contents)): ?>
            <?php foreach ($contents as $content): ?>
                <h2 id="<?= $content->slug ?>">
                    <a href=#<?= $content->slug ?>>
                        <?= ucfirst(Html::encode($content->name)) ?>
                    </a>
                </h2>
                <?= $content->content ?>
                <br>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <p>
                    Help content is not available
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

