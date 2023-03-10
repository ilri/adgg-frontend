<?php

use backend\modules\reports\models\AdhocReport;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\helpers\Url;
use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\reports\models\AdhocReport */

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => 'Ad-hoc Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-header row mr-0 ml-0">
        <div class="col-md-6 text-left"><h5><?= Lang::t('Report Information') ?> </h5></div>
        <div class="col-md-6 text-right">
            <h5 class="hidden">
                <?= Html::a('Re-run Report <i class="fas fa-redo"></i>', Url::to(['requeue', 'id' => $model->id]), ['title' => 'Re-add back to Queue', 'data-pjax' => 0, 'class' => 'grid-updatex', 'data-href' => Url::to(['requeue', 'id' => $model->id])]) ?>
            </h5>
        </div>
    </div>
    <div class="card-body">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table detail-view table-striped'],
            'attributes' => [
                [
                    'attribute' => 'id',
                ],
                [
                    'attribute' => 'name',
                ],
                [
                    'attribute' => 'raw_sql',
                    'visible' => false,
                ],
                [
                    'attribute' => 'status',
                    'value' => AdhocReport::decodeStatus($model->status)
                ],
                [
                    'attribute' => 'report_file',
                    'value' => Html::a($model->report_file  . ' <i class="fas fa-external-link"></i>', ['download-file', 'id' => $model->id], ['target' => '_blank']),
                    'format' => 'raw',
                    'visible' => $model->status == AdhocReport::STATUS_COMPLETED && $model->report_file !== null,
                ],
                [
                    'attribute' => 'status_remarks',
                ],
                [
                    'attribute' => 'created_at',
                    'value' => DateUtils::formatToLocalDate($model->created_at),
                ],
                [
                    'attribute' => 'created_by',
                    'value' => $model->getRelationAttributeValue('createdByUser', 'name'),
                ],
            ],
        ]) ?>
    </div>
</div>