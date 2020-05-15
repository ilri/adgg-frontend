<?php

use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\FarmMetadata;
use common\helpers\Lang;
use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $metadataModel FarmMetadata*/
/* @var $type  */
?>
<?php if ($metadataModel !== null): ?>
    <!-- $attributeModels = TableAttribute::find()->andWhere(['farm_metadata_type' => $type, 'is_active' => 1])->all();
     $attributeGroupIds = [];
     $attributeGroups = null;
     foreach ($attributeModels as $attrModel) {
         $attributeGroupIds[] = $attrModel->group_id;
     }
     $attributeGroups = array_unique($attributeGroupIds);
     $values= [];
     foreach ($attributeGroups as $key => $id) {
         $groupName = TableAttributesGroup::getScalar('name', ['id' => $id]);
         $attributes = TableAttribute::getData(['attribute_key', 'attribute_label', 'list_type_id', 'input_type'], ['farm_metadata_type' => $type, 'group_id' => 2]);
         $attributes = array_map(function ($attribute) {
             return [
                 'attribute' => $attribute['attribute_key']
             ];
         }, $attributes);
     }
 -->
    <div class="accordion accordion-outline" id="accordion1">
        <div class="card">
            <div class="card-header" id="heading1">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse1"
                     aria-expanded="false"
                     aria-controls="collapse1">
                    <?= Lang::t('Cattle feeding systems') ?>
                </div>
            </div>
            <div id="collapse1" class="card-body-wrapper collapse" aria-labelledby="heading1"
                 data-parent="#accordion1" style="">
                <div class="card-body">
                    <br/>
                    <?= DetailView::widget([
                        'model' => $metadataModel,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' => [
                            [
                                'attribute' => 'feeding_system_calves',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_FEEDING_SYSTEM,$metadataModel->feeding_system_calves ),

                            ],
                            [
                                'attribute' => 'feeding_system_immature_heifers',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_FEEDING_SYSTEM,$metadataModel->feeding_system_immature_heifers ),

                            ],
                            [
                                'attribute' => 'feeding_system_immature_male',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_FEEDING_SYSTEM,$metadataModel->feeding_system_immature_male ),

                            ],
                            [
                                'attribute' => 'feeding_system_mature',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_FEEDING_SYSTEM,$metadataModel->feeding_system_mature ),

                            ],
                            [
                                'attribute' => 'feed_lactation',
                                'format' => 'boolean',

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
                    <?= Lang::t('Grown improved fodder or pasture') ?>
                </div>
            </div>
            <div id="collapse2" class="card-body-wrapper collapse" aria-labelledby="heading2"
                 data-parent="#accordion2" style="">
                <div class="card-body">
                    <br/>
                    <?= DetailView::widget([
                        'model' => $metadataModel,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' => [
                            [
                                'attribute' => 'fodder_grow',
                                'format' => 'boolean',
                            ],
                            [
                                'attribute' => 'fodder_species',
                                'value' => Choices::getMultiSelectLabel($metadataModel->fodder_species,ChoiceTypes::CHOICE_TYPE_FODDER_INFORMATION ),
                            ],
                            [
                                'attribute' => 'fodder_species_other',
                            ],
                            [
                                'attribute' => 'fodder_proportion',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PROPORTION,$metadataModel->fodder_proportion ),
                            ],
                            [
                                'attribute' => 'fodder_acres',
                            ],
                            [
                                'attribute' => 'fodder_growing_annual_cost',
                            ],
                            [
                                'attribute' => 'fodder_information_source',
                                'value' => Choices::getMultiSelectLabel($metadataModel->fodder_information_source,ChoiceTypes::CHOICE_TYPE_FODDER_INFORMATION ),
                            ],
                            [
                                'attribute' => 'fodder_information_source_other',
                            ],
                            [
                                'attribute' => 'fodder_area_under_planted_grasses',
                            ],
                            [
                                'attribute' => 'fodder_area_under_naturalized_grasses',
                            ],
                            [
                                'attribute' => 'fodder_area_under_maize',
                            ],
                            [
                                'attribute' => 'fodder_area_under_shrubs',
                            ],
                            [
                                'attribute' => 'fodder_area_under_legumes',
                            ],
                            [
                                'attribute' => 'fodder_area_under_other',
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
                    <?= Lang::t('Purchased improved fodder') ?>
                </div>
            </div>
            <div id="collapse3" class="card-body-wrapper collapse" aria-labelledby="heading3"
                 data-parent="#accordion3" style="">
                <div class="card-body">
                    <br/>
                    <?= DetailView::widget([
                        'model' => $metadataModel,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' =>[
                            [
                                'attribute' => 'fodder_is_purchased',
                                'format'=>'boolean'
                            ],
                            [
                                'attribute' => 'fodder_species_purchased',
                                'value' => Choices::getMultiSelectLabel($metadataModel->fodder_species_purchased ,ChoiceTypes::CHOICE_TYPE_FODDER_SPECIES),
                            ],
                            [
                                'attribute'=> 'fodder_species_purchased_other',
                            ],
                            [
                                'attribute' => 'fodder_purchase_proportion',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PROPORTION,$metadataModel->fodder_purchase_proportion),
                            ],
                            [
                                'attribute' => 'fodder_purchase_months',
                            ],
                            [
                                'attribute' => 'fodder_purchase_annual_cost',
                            ],
                            [
                                'attribute'=> 'fodder_purchase_where',
                                'value' => Choices::getMultiSelectLabel($metadataModel->fodder_purchase_where,ChoiceTypes::CHOICE_TYPE_GRAIN_SOURCE),
                            ],
                            [
                                'attribute' => 'fodder_purchase_where_other',
                            ],
                            [
                                'attribute' => 'fodder_purchase_annual_transport_cost',
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
                    <?= Lang::t('Crop residues') ?>
                </div>
            </div>
            <div id="collapse4" class="card-body-wrapper collapse" aria-labelledby="heading4"
                 data-parent="#accordion4" style="">
                <div class="card-body">
                    <br/>
                    <?= DetailView::widget([
                        'model' => $metadataModel,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' =>[
                            [
                                'attribute' => 'crop_residue_feed_is_used',
                                'format'=>'boolean'
                            ],
                            [
                                'attribute' => 'residue_types',
                                'value' => Choices::getMultiSelectLabel($metadataModel->residue_types,ChoiceTypes::CHOICE_TYPE_RESIDUE_TYPES),
                            ],
                            [
                                'attribute'=> 'residue_types_other',
                            ],
                            [
                                'attribute' => 'residue_proportion',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PROPORTION,$metadataModel->residue_proportion),

                            ],
                            [
                                'attribute' => 'residue_source',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_RESIDUE_SOURCE,$metadataModel->residue_source ),
                            ],
                            [
                                'attribute'=> 'residue_annual_cost',
                            ],
                            [
                                'attribute' => 'residue_purchase_where',
                                'value' => Choices::getMultiSelectLabel($metadataModel->residue_purchase_where,ChoiceTypes::CHOICE_TYPE_RESIDUE_PURCHASE),
                            ],
                            [
                                'attribute' => 'residue_purchase_where_other',
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
                    <?= Lang::t('Concentrates feeds') ?>
                </div>
            </div>
            <div id="collapse5" class="card-body-wrapper collapse" aria-labelledby="heading5"
                 data-parent="#accordion5" style="">
                <div class="card-body">
                    <br/>
                    <?= DetailView::widget([
                        'model' => $metadataModel,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' =>[
                            [
                                'attribute' => 'concentrate_feed_is_used',
                                'format'=>'boolean'
                            ],
                            [
                                'attribute' => 'concentrate_types',
                                'value' => Choices::getMultiSelectLabel($metadataModel->concentrate_types,ChoiceTypes::CHOICE_TYPE_CONCENTRATE_TYPES ),
                            ],
                            [
                                'attribute'=> 'concentrate_types_other',

                            ],
                            [
                                'attribute' => 'concentrate_proportion',
                                'value' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_PROPORTION,$metadataModel->concentrate_proportion ),

                            ],
                            [
                                'attribute' => 'concentrate_cost',
                            ],
                            [
                                'attribute' => 'concentrate_purchase_where',
                                'value' => Choices::getMultiSelectLabel($metadataModel->concentrate_purchase_where ,ChoiceTypes::CHOICE_TYPE_CONCENTRATE_PURCHASE),
                            ],
                            [
                                'attribute'=> 'concentrate_purchase_where_other',
                            ],
                            [
                                'attribute' => 'concentrate_information_source',
                                'value' => Choices::getMultiSelectLabel($metadataModel->concentrate_information_source ,ChoiceTypes::CHOICE_TYPE_CONCENTRATE_INFORMATION),

                            ],
                            [
                                'attribute' => 'concentrate_information_source_other',
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
