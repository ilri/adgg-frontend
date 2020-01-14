<?php

use common\helpers\Lang;
use common\widgets\highchart\HighChart;

/* @var $graphFilterOptions array */
/* @var $this \yii\web\View */
?>
<div class="row">
    <div class="col-md-12">
        <div class="accordion" id="animal-stats-accordion" role="tablist">
            <div class="card">
                <h4 class="card-header" role="tab" id="headingOne" style="padding: 10px;">
                    <a role="button" data-toggle="collapse" data-parent="#savings-stats-accordion"
                       href="#savings-stats-collapse">
                        <i class="glyphicon glyphicon-chevron-right"></i> <?= Lang::t('Animal Graphical Stats') ?>
                    </a>
                </h4>

                <div id="savings-stats-collapse" class="panel-collapse collapse show" role="tabpanel">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>