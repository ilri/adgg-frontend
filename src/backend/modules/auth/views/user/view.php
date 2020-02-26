<?php

use backend\modules\auth\models\Users;
use common\helpers\DateUtils;
use common\helpers\Lang;
use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\auth\models\Users */

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_profileHeader', ['model' => $model]) ?>
<?php if ($model->status != Users::STATUS_ACTIVE): ?>
    <div class="alert alert-outline-danger fade show" role="alert">
        <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
        <div class="alert-text"><?= Lang::t('This account is {status}', ['status' => Users::decodeStatus($model->status)]) ?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
<?php endif; ?>
<div class="card">
    <h5 class="card-header"><?= Lang::t('Account Information') ?></h5>
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
                    'attribute' => 'username',
                ],
                [
                    'attribute' => 'email',
                ],
                [
                    'attribute' => 'phone',
                ],
                [
                    'attribute' => 'timezone',
                ],
                [
                    'attribute' => 'status',
                    'value' => Users::decodeStatus($model->status)
                ],
                [
                    'attribute' => 'level_id',
                    'value' => $model->getRelationAttributeValue('level', 'name'),
                ],
                [
                    'attribute' => 'role_id',
                    'value' => $model->getRelationAttributeValue('role', 'name'),
                ],
                [
                    'attribute' => 'country_id',
                    'value' => Html::a($model->getRelationAttributeValue('country', 'name') . ' <i class="fas fa-external-link"></i>', ['/core/country/view', 'id' => $model->country_id], ['target' => '_blank']),
                    'format' => 'raw',
                    'visible' => $model->country !== null && !\backend\modules\auth\Session::isCountry(),
                ],
                [
                    'attribute' => 'require_password_change',
                    'value' => \common\helpers\Utils::decodeBoolean($model->require_password_change),
                ],
                [
                    'attribute' => 'created_at',
                    'value' => DateUtils::formatToLocalDate($model->created_at),
                ],
                [
                    'attribute' => 'last_login',
                    'value' => DateUtils::formatToLocalDate($model->last_login),
                ],
            ],
        ]) ?>
    </div>
</div>