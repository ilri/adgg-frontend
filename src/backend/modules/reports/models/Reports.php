<?php

namespace backend\modules\reports\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\MilkingEvent;
use common\helpers\ArrayHelper;
use common\helpers\DateUtils;
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

        $row['cattletotalowned'] = floatval($row['total_cattle_owned_by_female']) + floatval($row['total_cattle_owned_by_male'] + floatval($row['total_cattle_owned_joint']));
        unset($row['total_cattle_owned_by_female'], $row['total_cattle_owned_by_male']);
        return $row;
    }

    public static function milkDataReport($filter){
        $fields = [
            'region_id' => null,
            'district_id' => null,
            'ward_id' => null,
            'village_id' => null,
            'region.name' => null,
            'district.name' => null,
            'ward.name' => null,
            'village.name' => null,
            'animal.farm.id' => null,
            'animal.farm.gender_code' => null,
            'animal.farm.total_cattle_owned' => null,
            'animal.farm.total_cattle_owned_by_female' => null,
            'animal.farm.total_cattle_owned_by_male' => null,
            'animal.farm.total_cattle_owned_joint' => null,
            'animal.tag_id' => null,
            'lactation.event_date' => null,
            'event_date' => null,
            'testday_no' => null,
            'milkmor' => null,
            'milkmid' => null,
            'milkeve' => null,
            'milkday' => null,
            'dim' => null,
            'milk_heartgirth' => null,
            'weight' => null,
            'milk_estimated_weight' => null,
            'milk_bodyscore' => null,
            'milkfat' => null,
            'milkprot' => null,
            'lactation.lactation_number' => null,
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
            'lactation.event_date' => 'CalvDate',
            'animal.tag_id' => 'animalid',
            'region.name' => 'Region',
            'district.name' => 'District',
            'ward.name' => 'Ward',
            'village.name' => 'Village',
            'animal.farm.id' => 'hh_id',
            'animal.farm.gender_code' => 'farmergender',
            'animal.farm.total_cattle_owned' => 'cattletotalowned',
            'animal.farm.total_cattle_owned_by_female' => 'total_cattle_owned_by_female',
            'animal.farm.total_cattle_owned_by_male' => 'total_cattle_owned_by_male',
            'animal.farm.total_cattle_owned_joint' => 'total_cattle_owned_joint',
            'event_date' => 'milkdate',
            'testday_no' => 'TDNo',
            'milkmor' => 'milkmor',
            'milkmid' => 'milkmid',
            'milkeve' => 'milkeve',
            'milkday' => 'Total_Milk',
            'dim' => 'DIM',
            'milk_heartgirth' => 'heartgirth',
            'weight' => 'weight',
            'milk_estimated_weight' => 'estimated weight',
            'milk_bodyscore' => 'bodyscore',
            'milkfat' => 'mlkfat',
            'milkprot' => 'mlkprot',
            'lactation.lactation_number' => 'LactID',
        ];
        $excludeFromReport = array_keys($filterValues);
        $genders = \backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_GENDER;
        $decodedFields = [
            'event_date' => [
                'function' => '\common\helpers\DateUtils::formatDate',
                'params' => [
                    'fieldValue',
                    'd/m/Y',
                ]
            ],
            'lactation.event_date' => [
                'function' => '\common\helpers\DateUtils::formatDate',
                'params' => [
                    'fieldValue',
                    'd/m/Y',
                ]
            ],
            'animal.farm.gender_code' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params'=> [
                    "$genders",
                    'fieldValue', // the value of this field
                ]
            ],
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
            'tag_id' => null,
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
            'region.name' => 'Region',
            'district.name' => 'District',
            'ward.name' => 'Ward',
            'village.name' => 'Village',
            'farm.id' => 'hh_id',
            'tag_id' => 'animalid',
            'sire_tag_id' => 'siretagid',
            'dam_tag_id' => 'damtagid',
            'animal_type' => 'sex_des',
            'birthdate' => 'birthdate',
            'main_breed' => 'breed',
            'animal_approxage' => 'approxage',
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
