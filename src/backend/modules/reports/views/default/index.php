<?php

/* @var $this yii\web\View */

use backend\modules\reports\models\Reports;
use common\helpers\Lang;
use common\helpers\Url;
use yii\bootstrap\Html;

$this->title = 'Reports Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="well">
            <h3 class="text-muted"><?= Lang::t('REPORTS DASHBOARD') ?></h3>
            <hr>
            <div class="row">
                <?php foreach (Reports::find()->andWhere(['is_active' => 1])->addOrderBy(['display_order' => SORT_ASC])->all() as $report): ?>
                    <div class="col-md-3 report-list">
                        <div class="panel panel-default bs-item z-depth-2">
                            <div class="panel-body">
                                <a href="<?= Url::to([$report->route]) ?>">
                              <span>
                                 <div class="report-name">
                                     <?= Html::encode($report->title) ?>
                                 </div>
                                 <p>
                                     <?= Html::encode($report->description) ?>
                                 </p>
                               </span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>