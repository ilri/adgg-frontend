<?php

use backend\modules\core\models\Organization;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\helpers\Utils;
use yii\bootstrap\Html;
use yii\helpers\Inflector;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $model Organization */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index', 'is_member' => $model->is_supplier, 'business_type' => $model->business_type]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_profileHeader', ['model' => $model]) ?>

<?php if ($model->status != Organization::STATUS_ACTIVE): ?>
    <div class="alert <?=$model->status==Organization::STATUS_PENDING_APPROVAL?'alert-outline-info':'alert-outline-danger'?> fade show" role="alert">
        <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
        <div class="alert-text"><?= Lang::t('This account is {status}', ['status' => $model->getDecodedStatus()]) ?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
<?php endif; ?>

<div class="accordion accordion-outline" id="accordion1">
    <div class="card">
        <div class="card-header" id="headingOne">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false"
                 aria-controls="collapseOne">
                Business Information
            </div>
        </div>
        <div id="collapseOne" class="card-body-wrapper collapse" aria-labelledby="headingOne"
             data-parent="#accordion1" style="">
            <div class="card-body">
                <br/>
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table detail-view table-striped'],
                    'attributes' => [
                        [
                            'attribute' => 'account_no',
                        ],
                        [
                            'attribute' => 'name',
                        ],
                        [
                            'attribute' => 'business_type',
                            'value' => $model->getDecodedBusinessType(),
                        ],
                        [
                            'attribute' => 'status',
                            'value' => $model->getDecodedStatus(),
                        ],
                        [
                            'attribute' => 'daily_customers',
                        ],
                        [
                            'attribute' => 'business_entity_type',
                            'value' => $model->getDecodedBusinessEntityType(),
                        ],
                        [
                            'attribute' => 'account_manager_id',
                            'value' => $model->getRelationAttributeValue('accountManager', 'name'),
                        ],
                        [
                            'attribute' => 'is_credit_requested',
                            'value' => Utils::decodeBoolean($model->is_credit_requested),
                            'visible' => $model->is_member,
                        ],
                        [
                            'attribute' => 'application_date',
                            'value' => DateUtils::formatDate($model->application_date, 'd-M-Y'),
                        ],
                        [
                            'attribute' => 'date_approved',
                            'value' => DateUtils::formatDate($model->date_approved, 'd-M-Y'),
                            'visible' => $model->is_approved,
                        ],
                        [
                            'attribute' => 'approval_notes',
                            'visible' => $model->is_approved,
                        ],
                        [
                            'attribute' => 'approved_by',
                            'value' => $model->getRelationAttributeValue('approvedBy', 'name'),
                            'visible' => $model->is_approved,
                        ],
                        [
                            'attribute' => 'membership_end_date',
                            'value' => DateUtils::formatDate($model->membership_end_date, 'd-M-Y'),
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
<br>
<div class="accordion accordion-outline" id="accordion2">
    <div class="card">
        <div class="card-header" id="headingTwo">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                 aria-controls="collapseTwo">
                Contact Person Information
            </div>
        </div>
        <div id="collapseTwo" class="card-body-wrapper collapse" aria-labelledby="headingTwo"
             data-parent="#accordion2" style="">
            <div class="card-body">
                <br/>
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table detail-view table-striped'],
                    'attributes' => [
                        [
                            'attribute' => 'contact_first_name',
                            'label' => 'Contact Person',
                            'value' => $model->getFullContactName(true, true),
                        ],
                        [
                            'attribute' => 'contact_phone',
                        ],
                        [
                            'attribute' => 'contact_alt_phone',
                        ],
                        [
                            'attribute' => 'contact_email',
                        ],
                        [
                            'attribute' => 'applicant_business_ownership_type',
                            'value' => $model->getDecodedBusinessOwnershipType(),
                        ],
                        [
                            'attribute' => 'applicant_email',
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
<br>
<div class="accordion accordion-outline" id="accordion3">
    <div class="card">
        <div class="card-header" id="headingThree">
            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                 aria-controls="collapseThree">
                Location Information
            </div>
        </div>
        <div id="collapseThree" class="card-body-wrapper collapse" aria-labelledby="headingThree"
             data-parent="#accordion3" style="">
            <div class="card-body">
                <br/>
                <div class="row">
                    <div class="col-lg-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table detail-view table-striped'],
                            'attributes' => [
                                [
                                    'attribute' => 'country',
                                ],
                                [
                                    'attribute' => 'county',
                                ],
                                [
                                    'attribute' => 'sub_county',
                                ],
                                [
                                    'attribute' => 'street',
                                ],
                                [
                                    'attribute' => 'postal_address',
                                ],
                            ],
                        ]) ?>
                    </div>
                    <div class="col-lg-6">
                        <?= \common\widgets\gmap\SingleViewWidget::widget([
                            'latitude' => $model->map_latitude,
                            'longitude' => $model->map_longitude,
                            'showDefaultMap' => false,
                            'mapWrapperHtmlOptions' => ['style' => 'height:400px;'],
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>