<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $contents \backend\modules\help\models\HelpContent[] */
$i = 0;
?>
<?php if (empty($contents)): ?>

    <div class="alert alert-info">
        <p>
            <i class="fa fa-info-circle"></i>&nbsp;Help content has not been published yet for this module
        </p>
    </div>

<?php else: ?>
    <?php foreach ($contents as $content): ?>
        <a href="#<?= $content->slug ?>" data-toggle="collapse" class="help-topic-referenced-section">
            <h3>
                <?= '# ' . Html::encode(ucfirst($content->name)) ?>
            </h3>
        </a>
        <div id="<?= $content->slug ?>" class="panel collapse">
            <?= $content['content'] ?>
        </div>
    <?php endforeach; ?>
    <?php if (isset($pager)): ?>
        <?= LinkPager::widget([
            'pagination' => $pager,
        ]);
        ?>
    <?php endif; ?>
<?php endif; ?>
