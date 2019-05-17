<?php

use common\widgets\detailView\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \backend\modules\help\models\HelpModules */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Help Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-modules-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'allowed_roles',
            'resource_name',
            'name',
            'slug',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'is_deleted',
            'deleted_at',
            'deleted_by',
            'is_active',
            'deactivated_at',
            'deactivated_by',
        ],
    ]) ?>

</div>
