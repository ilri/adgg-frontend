<?php

use common\helpers\DateUtils;
use common\helpers\Lang;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\core\models\Organization */

$this->title = Lang::t('Organization Details');
$this->params['breadcrumbs'][] = ['label' => 'Organizations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-header row mr-0 ml-0">
        <div class="col-md-6 text-left"><h5><?= $this->title ?> </h5></div>
        <div class="col-md-6 text-right">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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
                    'attribute' => 'country_id',
                    'value' => $model->getRelationAttributeValue('country', 'name'),
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