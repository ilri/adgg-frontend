<?php

use backend\modules\auth\Session;
use backend\modules\help\assets\PDFAsset;
use backend\modules\help\models\HelpContent;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $forAndroid  */

/* @var $models HelpContent[] */
$this->title = 'User Manual';
if ($forAndroid) {
    $this->title = 'Android App Manual';
}
if (Session::isPrivilegedAdmin()) {
    $this->params['breadcrumbs'][] = ['label' => 'Help Contents', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;

PDFAsset::register($this);
$format = Yii::$app->request->get('format', null);
?>
<div class="help-content-view">
    <?php if ($format === null): ?>
        <?= $this->render('_filter', ['filterOptions' => $filterOptions,'forAndroid' => $forAndroid,
        ]) ?>
    <?php endif; ?>
    <div class="accordion mb-5" id="accordion">
        <?php
        $i = 0;
        foreach ($models as $model):
            /* @var $model HelpContent */
            $i++;
        if ($format === 'word'){
            $model->content = preg_replace('/<img /', '<img width="600" ', $model->content);
        }
            ?>

            <?php if ((Session::getUserLevelId() == $model->user_level_id) || ($model->user_level_id == null) || Session::isPrivilegedAdmin()): ?>
            <?php if ($format === null): ?>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title" data-toggle="collapse" data-target="#collapse<?= $i ?>"
                             aria-expanded="true"
                             aria-controls="collapseOne">
                            <i class="fas fa-chevron-down"></i> <?= Html::encode($i . ' - ' . $model->name)  ?>:
                        </div>
                    </div>
                    <div id="collapse<?= $i ?>" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                            <div class="help-content-body mt-3 mb-3"><?= HtmlPurifier::process($model->content) ?></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="help-content-container card card-body mb-5">
                    <div class="help-content-container card card-body mb-5">
                        <div class="help-content-title"><h3><?= Html::encode($i . ' - ' . $model->name) ?></h3></div>
                        <div class="help-content-body mt-3 mb-3"><?= HtmlPurifier::process($model->content) ?></div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>