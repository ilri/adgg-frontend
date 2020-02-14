<?php

use common\helpers\DateUtils;
use common\helpers\Lang;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\core\models\OdkJsonQueue */

$this->title = Lang::t('JSON File Details');
$this->params['breadcrumbs'][] = ['label' => 'ODK-JSON-Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-header row mr-0 ml-0">
        <div class="col-md-6 text-left"><h5><?= Lang::t('Details') ?> </h5></div>
        <div class="col-md-6 text-right">
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
                    'attribute' => 'uuid',
                ],
                [
                    'attribute' => 'file',
                ],
                [
                    'attribute' => 'file_contents',
                ],
                [
                    'attribute' => 'is_processed',
                    'format' => 'boolean',
                ],
                [
                    'attribute' => 'processed_at',
                    'value' => DateUtils::formatToLocalDate($model->processed_at),
                ],
                [
                    'attribute' => 'org_id',
                    'value' => $model->getRelationAttributeValue('org', 'name'),
                ],
                [
                    'attribute' => 'has_errors',
                    'format' => 'boolean',
                ],
                [
                    'attribute' => 'error_message',
                ],
                [
                    'attribute' => 'is_locked',
                    'format' => 'boolean',
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