<?php
/* @var $this \yii\web\View */
/* @var $filterOptions array */
?>
{filter_form}
<hr/>
<div class="row">
    <div class="col-md-2" id="farms-summary-stats">
        <?= $this->render('_stats', ['filters' => $filterOptions]) ?>
    </div>
    <div class="col-md-10">
        <div class="well well-sm well-light">
            {chart}
        </div>
    </div>
</div>