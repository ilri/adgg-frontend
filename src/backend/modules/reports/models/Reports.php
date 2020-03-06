<?php

namespace backend\modules\reports\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\MilkingEvent;
use common\helpers\ArrayHelper;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use yii\db\Expression;

/**
 * This is the model class for table "reports".
 *
 * @property int $id
 * @property string $code
 * @property string $title
 * @property string $description
 * @property int $send_scheduled_report
 * @property int $is_active
 * @property int $display_order
 * @property string $route
 * @property string $resource_key
 * @property string $created_at
 * @property int $created_by
 */
class Reports extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reports}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'title', 'description', 'route'], 'required'],
            [['send_scheduled_report', 'is_active', 'display_order'], 'integer'],
            [['resource_key'], 'string', 'max' => 128],
            [['title', 'route'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
            [['code'], 'unique'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Lang::t('#'),
            'code' => Lang::t('Code'),
            'title' => Lang::t('Title'),
            'description' => Lang::t('Description'),
            'send_scheduled_report' => Lang::t('Send Scheduled Report'),
            'is_active' => Lang::t('Active'),
            'display_order' => Lang::t('Display Order'),
            'route' => Lang::t('Route'),
            'resource_key' => Lang::t('Resource Key'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function searchParams()
    {
        return [
            ['title', 'title'],
            'is_active',
            'send_scheduled_report',
            'display_order',
            'code',
            'resource_key',
        ];
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getNextDisplayOrder()
    {
        $max = (int)static::getScalar(['max([[display_order]])']);
        return $max + 1;
    }

    public static function transformMilkDataRow($row, $options = []){
        $fieldAliasMapping = $options['fieldAliasMapping'] ?? [];

        //$row['Total Milk'] = floatval($row[$fieldAliasMapping['milkmor']]) + floatval($row[$fieldAliasMapping['milkmid']] ) + floatval($row[$fieldAliasMapping['milkeve']]);

        return $row;
    }

    public static function milkDataReport($filter){
        $fields = [
            'animal.tag_id'=> null,
            'dry_date'=> null,
            'milk_animalcalvdate' =>null,
            'event_date' => null,
            'milkmor' => null,
            'milkmid' => null,
            'milkeve' => null,
            'milk_quality' => null,
            'milk_sample_type' => null,
            'milkfat' => null,
            'milkprot' => null,
            'milksmc' => null,
            'milk_notes' => null,
            'date_serviced' => null,
            'service_type' => null,
            'service_source' => null,
            'service_source_other' => null,
            'service_cost' => null,
            'sire_tag_id' => null,
            'sire_name' => null,
            'sire_country' => null,
            'sire_breed' => null,
            'sire_breed_other' => null,
            'sire_breed_composition' => null,
            'sire_breed_composition_other' => null,
            'straw_id' => null,
            'straw_country' => null,
            'straw_breed' => null,
            'straw_breed_other' => null,
            'straw_breed_composition' => null,
            'straw_breed_composition_other' => null,
            'vaccination_date' => null,
            'vaccination_type' => null,
            'vaccination_type_other' => null,
            'vaccination_service_provider' => null,
            'vaccination_service_provider_other' => null,
            'vaccine_drug_cost' => null,
            'vaccination_service_cost' => null,
            'parasite_treatment_date' => null,
            'parasite_type' => null,
            'parasite_type_other' => null,
            'parasite_treatment_service_provider' => null,
            'parasite_treatment_service_provider_other' => null,
            'parasite_treatment_total_cost' => null,
            'parasite_treatment_service_cost' => null,
            'injury_date' => null,
            'injury_type' => null,
            'injury_type_other' => null,
            'injury_treatment_service_provider' => null,
            'injury_treatment_service_provider_other' => null,
            'injury_treatment_total_cost' => null,
            'injury_treatment_service_cost' => null,
            'feed_type' => null,
            'water_type' => null,
            'milking_heartgirth' => null,
            'weight' => null,
            'milking_bodyscore' => null,
            'milkurea' => null,
            'milklact' => null,
            'milking_ai_service_source' => null,
            'bull_service_source' => null,
            'paid_straw_id' => null,
            'land_size' => null,
            'lactation.event_date' => null,
            'calf_status' => null,
            'calf_tag_id' => null,
            'region_id' => null,
            'district_id' => null,
            'ward_id' => null,
            'village_id' => null,
        ];
        $filterConditions = array_merge($fields, [
            'region_id' => '=',
            'district_id' => '=',
            'ward_id' => '=',
            'village_id' => '=',
        ]);
        $filterValues = [
            'region_id' => $filter['region_id'],
            'district_id' => $filter['district_id'],
            'ward_id' => $filter['ward_id'],
            'village_id' => $filter['village_id'],
        ];
        $fieldAliases = [
            'region.name' => 'Region Name',
            'district.name' => 'District Name',
            'ward.name' => 'Ward Name',
            'village.name' => 'Village Name',
            'animal.tag_id' => 'animalid',
            'dry_date' => 'drydate',
            'milk_animalcalvdate' => 'calvdate',
            'event_date' => 'milkdate',
            'milkmor' => 'milkmor',
            'milkmid' => 'milkmid',
            'milkeve' => 'milkeve',
            'milk_quality' => 'milkqlty',
            'milk_sample_type' => 'mlksmptype',
            'milkfat' => 'mlkfat',
            'milkprot' => 'mlkprot',
            'milksmc' => 'mlksmc',
            'milk_notes' => 'mlknotes',
            'date_serviced' => 'dateserv',
            'service_type' => 'servtype',
            'service_source' => 'servsource',
            'service_source_other' => 'servsourceoth',
            'service_cost' => 'sercost',
            'sire_tag_id' => 'siretag',
            'sire_name' => 'sirename',
            'sire_country' => 'sirecnty',
            'sire_breed' => 'sirebreed',
            'sire_breed_other' => 'sirebreedoth',
            'sire_breed_composition' => 'sirecomp',
            'sire_breed_composition_other' => 'sirecompoth',
            'straw_id' => 'strawid',
            'straw_country' => 'strawcnty',
            'straw_breed' => 'strawbreed',
            'straw_breed_other' => 'strawbreedoth',
            'straw_breed_composition' => 'strawcomp',
            'straw_breed_composition_other' => 'strawcompoth',
            'vaccination_date' => 'vaccdate',
            'vaccination_type' => 'vaccinetype',
            'vaccination_type_other' => 'vaccinetypeoth',
            'vaccination_service_provider' => 'vaccsvrprov',
            'vaccination_service_provider_other' => 'vaccsvrprovoth',
            'vaccine_drug_cost' => 'vaccdcost',
            'vaccination_service_cost' => 'vaccscost',
            'parasite_treatment_date' => 'paradate',
            'parasite_type' => 'parasitetype',
            'parasite_type_other' => 'parasitetypeoth',
            'parasite_treatment_service_provider' => 'parasvrprov',
            'parasite_treatment_service_provider_other' => 'parasvrprovoth',
            'parasite_treatment_total_cost' => 'paratcost',
            'parasite_treatment_service_cost' => 'parascost',
            'injury_date' => 'injurydate',
            'injury_type' => 'injurytype',
            'injury_type_other' => 'injurytypeoth',
            'injury_treatment_service_provider' => 'injurysvrprov',
            'injury_treatment_service_provider_other' => 'injurysvrprovoth',
            'injury_treatment_total_cost' => 'injurytcost',
            'injury_treatment_service_cost' => 'injuryscost',
            'feed_type' => 'feedtype',
            'water_type' => 'watertype',
            'milking_heartgirth' => 'heartgirth',
            'weight' => 'weight',
            'milking_bodyscore' => 'bodyscore',
            'milkurea' => 'milkurea',
            'milklact' => 'milklact',
            'milking_ai_service_source' => 'servsourceai',
            'bull_service_source' => 'servsourcebull',
            'paid_straw_id' => 'paidstrawid',
            'land_size' => 'landsize',
            'lactation.event_date' => 'calvngdate',
            'calf_status' => 'calfstatus',
            'calf_tag_id' => 'calftagid',
        ];
        $excludeFromReport = array_keys($filterValues);

        $decodedFields = [
            'event_date' => [
                'function' => '\common\helpers\DateUtils::formatDate',
                'params' => [
                    'fieldValue',
                    'd/m/Y',
                ]
            ]
        ];

        $from = ArrayHelper::getValue($filter, 'from');
        $to = ArrayHelper::getValue($filter, 'to');

        $orderBy = '';

        $builder = new ReportBuilder();
        $builder->model = 'Milking_Event';
        $builder->filterConditions = $filterConditions;
        $builder->filterValues = $filterValues;
        $builder->fieldAliases = $fieldAliases;
        $builder->excludeFromReport = $excludeFromReport;
        $builder->decodeFields = $decodedFields;
        $builder->orderBy = $orderBy;
        //$builder->limit = 50;
        $builder->country_id = $filter['country_id'] ?? null;
        $builder->name = 'Milk Data';

        if (!empty($from) && !empty($to)) {
            $casted_date = DbUtils::castDATE(MilkingEvent::tableName().'.[[event_date]]');
            $condition = '(' . $casted_date . '>=:from AND ' . $casted_date . '<=:to)';
            $params[':from'] = $from;
            $params[':to'] = $to;
            $expression = new Expression($condition, $params);
            $builder->extraFilterExpressions[] = $expression;
        }
        // add the rowTransformer
        $builder->rowTransformer = '\backend\modules\reports\models\Reports::transformMilkDataRow';

        return $builder;
    }

    public static function pedigreeDataReport($filter){
        $fields = [
            'region_id' => null,
            'district_id' => null,
            'ward_id' => null,
            'village_id' => null,
            'region.name' => null,
            'district.name' => null,
            'ward.name' => null,
            'village.name' => null,
            'farm.id' => null,
            'id' => null,
            'sire_tag_id' => null,
            'dam_tag_id' => null,
            'animal_type' => null,
            'birthdate' => null,
            'main_breed' => null,
            'animal_approxage' => null,
            //'deformities' => null,
            //'farm.gender_code' => null,
            //'farm.farmer_is_hh_head' => null,
        ];
        $filterConditions = array_merge($fields, [
            'region_id' => '=',
            'district_id' => '=',
            'ward_id' => '=',
            'village_id' => '=',
        ]);
        $filterValues = [
            'region_id' => $filter['region_id'],
            'district_id' => $filter['district_id'],
            'ward_id' => $filter['ward_id'],
            'village_id' => $filter['village_id'],
        ];
        $fieldAliases = [
            'region.name' => 'Region Name',
            'district.name' => 'District Name',
            'ward.name' => 'Ward Name',
            'village.name' => 'Village Name',
            'farm.id' => 'Farm ID',
            'id' => 'Animal ID',
            'sire_tag_id' => 'Sire Tag ID',
            'dam_tag_id' => 'Dam Tag ID',
            'animal_type' => 'Animal Type',
            'birthdate' => 'Birth Date',
            'main_breed' => 'Breed',
            'animal_approxage' => 'Approximate Age',
        ];
        # TODO: define these fields to be decoded elsewhere
        $breeds = \backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS;
        $animal_type = \backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES;
        $genders = \backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_GENDER;
        $decodedFields = [
            'main_breed' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params'=> [
                    //'\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS',
                    "$breeds",
                    'fieldValue', // the value of this field
                ]
            ],
            'animal_type' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params'=> [
                    //'\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS',
                    "$animal_type",
                    'fieldValue', // the value of this field
                ]
            ],
            'deformities' => [
                'function' => '\backend\modules\core\models\Animal::decodeDeformities',
                'params'=> [
                    'fieldValue', // the value of this field
                ]
            ],
            'farm.gender_code' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params'=> [
                    "$genders",
                    'fieldValue', // the value of this field
                ]
            ],
            'farm.farmer_is_hh_head' => [
                'function' => '\common\helpers\Utils::decodeBoolean',
                'params'=> [
                    'fieldValue', // the value of this field
                ]
            ],
            'birthdate' => [
                'function' => '\common\helpers\DateUtils::formatDate',
                'params' => [
                    'fieldValue',
                    'd/m/Y',
                ]
            ]
        ];

        $excludeFromReport = array_keys($filterValues);

        $from = ArrayHelper::getValue($filter, 'from');
        $to = ArrayHelper::getValue($filter, 'to');

        $orderBy = '';

        $builder = new ReportBuilder();
        $builder->model = 'Animal';
        $builder->filterConditions = $filterConditions;
        $builder->filterValues = $filterValues;
        $builder->fieldAliases = $fieldAliases;
        $builder->excludeFromReport = $excludeFromReport;
        $builder->decodeFields = $decodedFields;
        $builder->orderBy = $orderBy;
        //$builder->limit = 50;
        $builder->country_id = $filter['country_id'] ?? null;
        $builder->name = 'Pedigree';

        if (!empty($from) && !empty($to)) {
            $casted_date = DbUtils::castDATE(Animal::tableName().'.[[birthdate]]');
            $condition = '(' . $casted_date . '>=:from AND ' . $casted_date . '<=:to)';
            $params[':from'] = $from;
            $params[':to'] = $to;
            $expression = new Expression($condition, $params);
            $builder->extraFilterExpressions[] = $expression;
        }

        //dd($builder->rawQuery());

        return $builder;

    }
}
