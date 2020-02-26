<?php

use common\helpers\DateUtils;
use common\helpers\Lang;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\core\models\Client */

$this->title = Lang::t('Client Details');
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['index']];
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
                    'attribute' => 'name',
                ],
                [
                    'attribute' => 'description',
                ],
                [
                    'attribute' => 'country_id',
                    'value' => $model->getRelationAttributeValue('country', 'name'),
                ],
                [
                    'attribute' => 'org_id',
                    'value' => $model->getRelationAttributeValue('organization', 'name'),

                ],
                [
                    'attribute' => 'is_active',
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