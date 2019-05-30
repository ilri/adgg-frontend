<?php

use backend\modules\auth\models\AuditTrail;
use backend\modules\auth\models\Users;
use common\helpers\DateUtils;
use common\helpers\Lang;
use Illuminate\Support\Str;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $model AuditTrail */

$this->title = Lang::t('Audit Trail #{id}', ['id' => $model->id]);
?>
<div class="modal-header">
    <h5 class="modal-title"><?= Html::encode($this->title); ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="alert alert-outline-dark" role="alert">
        <div class="alert-text"><strong><?= Html::encode($model->action_description) ?></strong></div>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'action',
                'value' => AuditTrail::decodeAction($model->action),
            ],
            [
                'attribute' => 'url',
            ],
            [
                'attribute' => 'user_agent',
            ],
            [
                'attribute' => 'ip_address',
            ],
            [
                'attribute' => 'user_id',
                'value' => Users::getFieldByPk($model->user_id, 'name'),
            ],
            [
                'attribute' => 'created_at',
                'value' => DateUtils::formatToLocalDate($model->created_at),
            ],

        ],
    ]) ?>
    <div class="card">
        <h5 class="card-header"><?= Lang::t('Data modification') ?></h5>
        <div class="card-body">
            <div style="max-height: 200px;overflow-y: auto">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Field</th>
                        <th>Value Before</th>
                        <th>Value After</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ((array)@unserialize($model->details) as $field => $details):
                        if (empty($details['old']) && empty($details['new'])) {
                            continue;
                        }
                        ?>
                        <tr>
                            <td><?= $field ?></td>
                            <td><?= Html::encode(Str::limit(is_array($details['old']) ? Json::encode($details['old']) : $details['old'], 200, ' ...')) ?></td>
                            <td><?= Html::encode(Str::limit(is_array($details['new']) ? Json::encode($details['new']) : $details['new'], 200, ' ...')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>