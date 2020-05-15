<?php

use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\FarmMetadata;
use common\helpers\Lang;
use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $metadataModel FarmMetadata */
/* @var $type */

?>
<?php if ($metadataModel !== null): ?>
    <div class="accordion accordion-outline" id="accordion1">
        <div class="card">
            <div class="card-header" id="heading1">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse1"
                     aria-expanded="false"
                     aria-controls="collapse1">
                    <?= Lang::t('Details for Deworming') ?>
                </div>
            </div>
            <div id="collapse1" class="card-body-wrapper collapse" aria-labelledby="heading1"
                 data-parent="#accordion1" style="">
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $metadataModel,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' => [
                            [
                                'attribute' => 'health_anth_usage',
                                'format' => 'boolean',
                            ],
                            [
                                'attribute' => 'health_anth_usage_count',
                            ],
                            [
                                'attribute' => 'health_anth_provider',
                                'value' => Choices::getMultiSelectLabel($metadataModel->health_anth_provider, ChoiceTypes::CHOICE_TYPE_HEALTH_PROVIDER),
                            ],
                            [
                                'attribute' => 'health_anth_provider_other',
                            ],
                            [
                                'attribute' => 'health_anth_total_expense',
                                'format' => ['decimal', 2],
                            ],
                            [
                                'attribute' => 'health_anth_decision_maker',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_HEALTH_DECISION, $metadataModel->health_anth_decision_maker),
                            ],
                            [
                                'attribute' => 'health_anth_decision_maker_other',

                            ],
                            [
                                'attribute' => 'health_anth_last_intervention',
                                'format'=>['date', 'php:m/d/Y']
                            ],
                            [
                                'attribute' => 'health_anth_provider_name',
                            ],
                            [
                                'attribute' => 'health_anth_contact_is_known',
                                'format' => 'boolean',
                            ],
                            [
                                'attribute' => 'health_anth_mobile_no',
                            ],
                        ]
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="accordion accordion-outline" id="accordion2">
        <div class="card">
            <div class="card-header" id="heading2">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse2"
                     aria-expanded="false"
                     aria-controls="collapse2">
                    <?= Lang::t('Details for External Parasite Control / Tick Control') ?>
                </div>
            </div>
            <div id="collapse2" class="card-body-wrapper collapse" aria-labelledby="heading2"
                 data-parent="#accordion2" style="">
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $metadataModel,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' => [
                            [
                                'attribute' => 'health_tick_usage',
                            ],
                            [
                                'attribute' => 'health_tick_disease_type',
                                'value' => Choices::getMultiSelectLabel($metadataModel->health_tick_disease_type, ChoiceTypes::CHOICE_TYPE_HEALTH_DISEASE),
                            ],
                            [
                                'attribute' => 'health_tick_disease_type_other',
                            ],
                            [
                                'attribute' => 'health_tick_control_usage_count',
                            ],
                            [
                                'attribute' => 'health_tick_control_method',
                                'value' => Choices::getMultiSelectLabel($metadataModel->health_tick_control_method, ChoiceTypes::CHOICE_TYPE_HEALTH_METHOD),
                            ],
                            [
                                'attribute' => 'health_tick_control_method_other',
                            ],
                            [
                                'attribute' => 'health_tick_control_provider',
                                'value' => Choices::getMultiSelectLabel($metadataModel->health_tick_control_provider, ChoiceTypes::CHOICE_TYPE_HEALTH_PROVIDER),
                            ],
                            [
                                'attribute' => 'health_tick_control_provider_other',
                            ],
                            [
                                'attribute' => 'health_tick_total_expense',
                                'format' => ['decimal', 2],
                            ],
                            [
                                'attribute' => 'health_tick_control_decision_maker',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_HEALTH_DECISION, $metadataModel->health_tick_control_decision_maker),

                            ],
                            [
                                'attribute' => 'health_tick_control_decision_maker_other',
                            ],
                            [
                                'attribute' => 'health_tick_control_last_intervention',
                                'format'=>['date', 'php:m/d/Y']
                            ],
                            [
                                'attribute' => 'health_tick_control_provider_name',
                            ],
                            [
                                'attribute' => 'health_tick_control_contact_is_known',
                                'format' => 'boolean',
                            ],
                            [
                                'attribute' => 'health_tick_control_mobile_no',
                            ],
                        ]
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="accordion accordion-outline" id="accordion3">
        <div class="card">
            <div class="card-header" id="heading3">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse3"
                     aria-expanded="false"
                     aria-controls="collapse3">
                    <?= Lang::t('Details for Vaccination') ?>
                </div>
            </div>
            <div id="collapse3" class="card-body-wrapper collapse" aria-labelledby="heading3"
                 data-parent="#accordion3" style="">
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $metadataModel,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' => [
                            [
                                'attribute' => 'health_vacc_usage',
                                'format'=>'boolean',
                            ],
                            [
                                'attribute' => 'health_vacc_disease_type',
                                'value' => Choices::getMultiSelectLabel($metadataModel->health_vacc_disease_type, ChoiceTypes::CHOICE_TYPE_HEALTH_DISEASE),
                            ],
                            [
                                'attribute' => 'health_vacc_disease_type_other',
                            ],
                            [
                                'attribute' => 'health_vacc_usage_count',
                            ],
                            [
                                'attribute' => 'health_vacc_provider',
                                'value' => Choices::getMultiSelectLabel($metadataModel->health_vacc_provider, ChoiceTypes::CHOICE_TYPE_HEALTH_PROVIDER),
                            ],
                            [
                                'attribute' => 'health_vacc_provider_other',
                            ],
                            [
                                'attribute' => 'health_vacc_total_expense',
                                'format' => ['decimal', 2],
                            ],
                            [
                                'attribute' => 'health_vacc_last_intervention',
                                'format'=>['date', 'php:m/d/Y']
                            ],
                            [
                                'attribute' => 'health_vacc_decision_maker',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_HEALTH_DECISION,$metadataModel->health_vacc_decision_maker),
                            ],
                            [
                                'attribute' => 'health_vacc_decision_maker_other',
                            ],
                            [
                                'attribute' => 'health_vacc_provider_name',
                            ],
                            [
                                'attribute' => 'health_vacc_contact_is_known',
                                'format'=>['date', 'php:m/d/Y']
                            ],
                            [
                                'attribute' => 'health_vacc_mobile_no',
                            ],
                        ]
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="accordion accordion-outline" id="accordion4">
        <div class="card">
            <div class="card-header" id="heading4">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse4"
                     aria-expanded="false"
                     aria-controls="collapse4">
                    <?= Lang::t('Details for Prophylactic Treatment') ?>
                </div>
            </div>
            <div id="collapse4" class="card-body-wrapper collapse" aria-labelledby="heading4"
                 data-parent="#accordion4" style="">
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $metadataModel,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' => [
                            [
                                'attribute' => 'health_prev_usage',
                                'format'=>'boolean'
                            ],
                            [
                                'attribute' => 'health_prev_disease_type',
                                'value' => Choices::getMultiSelectLabel($metadataModel->health_prev_disease_type, ChoiceTypes::CHOICE_TYPE_HEALTH_DISEASE),
                            ],
                            [
                                'attribute' => 'health_prev_disease_type_other',
                            ],
                            [
                                'attribute' => 'health_prev_usage_count',
                            ],
                            [
                                'attribute' => 'health_prev_provider',
                                'value' => Choices::getMultiSelectLabel($metadataModel->health_prev_provider, ChoiceTypes::CHOICE_TYPE_HEALTH_PROVIDER),
                            ],
                            [
                                'attribute' => 'health_prev_provider_other',
                            ],
                            [
                                'attribute' => 'health_prev_total_expense',
                                'format' => ['decimal', 2],
                            ],
                            [
                                'attribute' => 'health_prev_last_intervention',
                                'format'=>['date', 'php:m/d/Y']
                            ],
                            [
                                'attribute' => 'health_prev_decision_maker',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_HEALTH_DECISION,$metadataModel->health_prev_decision_maker),
                            ],
                            [
                                'attribute' => 'health_prev_decision_maker_other',
                            ],
                            [
                                'attribute' => 'health_prev_provider_name',
                            ],
                            [
                                'attribute' => 'health_prev_contact_is_known',
                                'format'=>'boolean',
                            ],
                            [
                                'attribute' => 'health_prev_mobile_no',
                            ],
                        ]
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="accordion accordion-outline" id="accordion5">
        <div class="card">
            <div class="card-header" id="heading5">
                <div class="card-title collapse5" data-toggle="collapse" data-target="#collapse5"
                     aria-expanded="false"
                     aria-controls="collapse5">
                    <?= Lang::t('Details for other Type of Treatment') ?>
                </div>
            </div>
            <div id="collapse5" class="card-body-wrapper collapse" aria-labelledby="heading5"
                 data-parent="#accordion5" style="">
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $metadataModel,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' => [
                            [
                                'attribute' => 'health_other_usage',
                                'format'=>'boolean'
                            ],
                            [
                                'attribute' => 'health_other_name',
                            ],
                            [
                                'attribute' => 'health_other_disease_type',
                                'value' => Choices::getMultiSelectLabel($metadataModel->health_other_disease_type, ChoiceTypes::CHOICE_TYPE_HEALTH_DISEASE),
                            ],
                            [
                                'attribute' => 'health_other_disease_type_other',
                            ],
                            [
                                'attribute' => 'health_other_usage_count',
                            ],
                            [
                                'attribute' => 'health_other_provider',
                                'value' => Choices::getMultiSelectLabel($metadataModel->health_other_provider, ChoiceTypes::CHOICE_TYPE_HEALTH_PROVIDER),
                            ],
                            [
                                'attribute' => 'health_other_provider_other',
                            ],
                            [
                                'attribute' => 'health_other_total_expense',
                                'format' => ['decimal', 2],
                            ],
                            [
                                'attribute' => 'health_other_last_intervention',
                                'format'=>['date', 'php:m/d/Y']
                            ],
                            [
                                'attribute' => 'health_other_decision_maker',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_HEALTH_DECISION,$metadataModel->health_other_decision_maker),
                            ],
                            [
                                'attribute' => 'health_other_decision_maker_other',
                            ],
                            [
                                'attribute' => 'health_other_provider_name',
                            ],
                            [
                                'attribute' => 'health_other_contact_is_known',
                                'format'=>'boolean',
                            ],
                            [
                                'attribute' => 'health_other_mobile_no',
                            ],
                        ]
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br>
<?php else: ?>
    <?= '<h4>'.Lang::t('No {metadataType} Data for this farm', ['metadataType' => Html::encode(FarmMetadata::decodeType($type))]).'</h4>' ?>
<?php endif; ?>
