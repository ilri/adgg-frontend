<?php

namespace backend\modules\reports\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\Country;
use backend\modules\core\models\MilkingEvent;
use backend\modules\core\models\WeightEvent;
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

    public static function isEmptyColumn($column)
    {
        return ($column == 'null' || $column == 'Null' || $column == 'NULL' || empty($column));
    }

    public static function transformMilkDataRow($row, $options = [])
    {
        $fieldAliasMapping = $options['fieldAliasMapping'] ?? [];

        $row['cattletotalowned'] = floatval($row['total_cattle_owned_by_female']) + floatval($row['total_cattle_owned_by_male']) + floatval($row['total_cattle_owned_joint']);
        unset($row['total_cattle_owned_by_female'], $row['total_cattle_owned_by_male'], $row['total_cattle_owned_joint']);

        if (static::isEmptyColumn($row['heartgirth'])) {
            $row['heartgirth'] = '9999';
        }
        if (static::isEmptyColumn($row['weight'])) {
            $row['weight'] = '9999';
        }
        if (static::isEmptyColumn($row['estimated weight'])) {
            $row['estimated weight'] = '9999';
        }
        if (static::isEmptyColumn($row['bodyscore'])) {
            $row['bodyscore'] = '9999';
        }
        return $row;
    }

    public static function transformTestDayMilkDataRow($row, $options = [])
    {
        $fieldAliasMapping = $options['fieldAliasMapping'] ?? [];

        $row['Cattleowned'] = floatval($row['total_cattle_owned_by_female']) + floatval($row['total_cattle_owned_by_male']) + floatval($row['total_cattle_owned_joint']);
        unset($row['total_cattle_owned_by_female'], $row['total_cattle_owned_by_male'], $row['total_cattle_owned_joint']);

        if (static::isEmptyColumn($row['Cattleowned'])) {
            $row['Cattleowned'] = '9999';
        }
        if (static::isEmptyColumn($row['milkAM'])) {
            $row['milkAM'] = '9999';
        }
        if (static::isEmptyColumn($row['milkPM'])) {
            $row['milkPM'] = '9999';
        }
        if (static::isEmptyColumn($row['TotalMilk'])) {
            $row['TotalMilk'] = '9999';
        }
        if (static::isEmptyColumn($row['DIM'])) {
            $row['DIM'] = '9999';
        }
        if (static::isEmptyColumn($row['MilkFat'])) {
            $row['MilkFat'] = '9999';
        }
        if (static::isEmptyColumn($row['MilkProt'])) {
            $row['MilkProt'] = '9999';
        }
        if (static::isEmptyColumn($row['HeartGirth'])) {
            $row['HeartGirth'] = '9999';
        }
        if (static::isEmptyColumn($row['Weight'])) {
            $row['Weight'] = '9999';
        }
        if (static::isEmptyColumn($row['estimated weight'])) {
            $row['estimated weight'] = '9999';
        }
        if (static::isEmptyColumn($row['Bodyscore'])) {
            $row['Bodyscore'] = '9999';
        }
        // if report is version 2, unset Wareda and Kebele
        if (array_key_exists('version', $options) && $options['version'] == 2) {
            unset($row['Wareda'], $row['Kebele']);
            // set AnimalId to be value of AnimalRegID (tag_id) and unset AnimalRegId
            $row['AnimalID'] = $row['AnimalRegID'];
        } else {
            unset($row['Ward'], $row['Village']);
        }
        unset($row['AnimalRegID']);

        return $row;
    }

    public static function transformCalfDataRow($row, $options = [])
    {
        $fieldAliasMapping = $options['fieldAliasMapping'] ?? [];

        $row['Cattleowned'] = floatval($row['total_cattle_owned_by_female']) + floatval($row['total_cattle_owned_by_male']) + floatval($row['total_cattle_owned_joint']);
        unset($row['total_cattle_owned_by_female'], $row['total_cattle_owned_by_male'], $row['total_cattle_owned_joint']);

        if (static::isEmptyColumn($row['Cattleowned'])) {
            $row['Cattleowned'] = '9999';
        }
        if (static::isEmptyColumn($row['HeartGirth'])) {
            $row['HeartGirth'] = '9999';
        }
        if (static::isEmptyColumn($row['Weight'])) {
            $row['Weight'] = '9999';
        }
        if (static::isEmptyColumn($row['Bodyscore'])) {
            $row['Bodyscore'] = '9999';
        }
       /* if (static::isEmptyColumn($row['estimated weight'])) {
            $row['estimated weight'] = '9999';
        }*/
        if (static::isEmptyColumn($row['Sex'])) {
            $row['Sex'] = '';
        }
        return $row;
    }

    public static function transformPedigreeFileRow($row, $options = [])
    {
        // if report is version 2, unset Wareda and Kebele
        if (array_key_exists('version', $options) && $options['version'] == 2) {
            //unset($row['Wareda'], $row['Kebele']);
            unset($row['SireID'], $row['DamID']);
        } else {
            unset($row['SireRegID'], $row['DamRegID']);
            //unset($row['Ward'], $row['Village']);
        }

        if (static::isEmptyColumn($row['HeartGirth'])) {
            $row['HeartGirth'] = '9999';
        }
        if (static::isEmptyColumn($row['EstimatedWeight'])) {
            $row['EstimatedWeight'] = '9999';
        }
        if (static::isEmptyColumn($row['BodyScore'])) {
            $row['BodyScore'] = '9999';
        }

        return $row;
    }

    public static function milkDataReport($filter)
    {
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
            //'milk_heartgirth' => null,
            //'weight' => null,
            //'milk_estimated_weight' => null,
            //'milk_bodyscore' => null,
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
                'params' => [
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
        $builder->name = 'Milk_Data_' . ($filter['country_id'] ? Country::getScalar('name', ['id' => $filter['country_id']]) : '');

        if (!empty($from) && !empty($to)) {
            $casted_date = DbUtils::castDATE(MilkingEvent::tableName() . '.[[event_date]]');
            $condition = '(' . $casted_date . '>=:from AND ' . $casted_date . '<=:to)';
            $params[':from'] = $from;
            $params[':to'] = $to;
            $expression = new Expression($condition, $params);
            $builder->extraFilterExpressions[] = $expression;
        }
        // add the rowTransformer
        $builder->rowTransformer = '\backend\modules\reports\models\Reports::transformMilkDataRow';

        $builder->extraJoins = [
            'weight' => [
                'core_animal_event',
                '[[core_animal_event]].[[data_collection_date]] = [[weight.data_collection_date]] AND [[weight]].[[event_type]] = 6 AND [[weight]].[[animal_id]] = [[core_animal_event]].[[animal_id]]'
            ],
        ];
        $weightFields = [
            'heartgirth' => 'heartgirth',
            'weight_kg' => 'weight',
            'body_score' => 'bodyscore',
            'estimated_weight' => 'estimated weight'
        ];
        foreach ($weightFields as $weightField => $alias) {
            $field = ReportBuilder::getFullColumnName($weightField, new WeightEvent(), $alias, true, 'weight');
            $builder->extraSelectExpressions[] = new Expression($field);
        }

        return $builder;
    }

    public static function testDayMilkDataReport($filter, $version = 1)
    {
        $fields = [
            'region_id' => null,
            'district_id' => null,
            'ward_id' => null,
            'village_id' => null,
            'region.name' => null,
            'district.name' => null,
            'ward.code' => null,
            'village.code' => null,
            //'ward.name' => null,
            //'village.name' => null,
            'animal.farm.id' => null,
            'animal.farm.gender_code' => null,
            'animal.farm.total_cattle_owned' => null,
            'animal.farm.total_cattle_owned_by_female' => null,
            'animal.farm.total_cattle_owned_by_male' => null,
            'animal.farm.total_cattle_owned_joint' => null,
            'animal.id' => null,
            'animal.tag_id' => null,
            'lactation.event_date' => null,
            'event_date' => null,
            'milkmor' => null,
            'milkeve' => null,
            'milkday' => null,
            'dim' => null,
            'milkfat' => null,
            'milkprot' => null,
            'lactation.lactation_number' => null,
            'testday_no' => null,
//            'animal.longitude' => null,
//            'animal.latitude' => null,
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
            'animal.id' => 'AnimalID',
            'animal.tag_id' => 'AnimalRegID',
            'region.name' => 'Region',
            'district.name' => 'District',
            'ward.code' => $version == 2 ? 'Ward' :'Wareda',
            'village.code' => $version == 2 ? 'Village' :'Kebele',
            //'ward.name' => 'Ward',
            //'village.name' => 'Village',
            'animal.farm.id' => 'HH_ID',
            'animal.farm.gender_code' => 'FarmerGender',
            'animal.farm.total_cattle_owned' => 'Cattleowned',
            'animal.farm.total_cattle_owned_by_female' => 'total_cattle_owned_by_female',
            'animal.farm.total_cattle_owned_by_male' => 'total_cattle_owned_by_male',
            'animal.farm.total_cattle_owned_joint' => 'total_cattle_owned_joint',
            'event_date' => 'milkdate',
            'milkmor' => 'milkAM',
            'milkeve' => 'milkPM',
            'milkday' => 'TotalMilk',
            'dim' => 'DIM',
            'milkfat' => 'MilkFat',
            'milkprot' => 'MilkProt',
            'milk_heartgirth' => 'HeartGirth',
            'weight' => 'Weight',
            'milk_estimated_weight' => 'estimated weight',
            'milk_bodyscore' => 'Bodyscore',
            'lactation.lactation_number' => 'LactNo',
            'testday_no' => 'TDNo',
//            'animal.longitude' => 'Longitude',
//            'animal.latitude' => 'Latitude',
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
                'params' => [
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
        $builder->name = 'TestDayMilk_Data_' . ($filter['country_id'] ? Country::getScalar('name', ['id' => $filter['country_id']]) : '');
        if ($version == 2) {
            $builder->name = $builder->name . '_v' . $version;
        }

        if (!empty($from) && !empty($to)) {
            $casted_date = DbUtils::castDATE(MilkingEvent::tableName() . '.[[event_date]]');
            $condition = '(' . $casted_date . '>=:from AND ' . $casted_date . '<=:to)';
            $params[':from'] = $from;
            $params[':to'] = $to;
            $expression = new Expression($condition, $params);
            $builder->extraFilterExpressions[] = $expression;
        }
        // add the rowTransformer
        $builder->rowTransformer = '\backend\modules\reports\models\Reports::transformTestDayMilkDataRow';

        $builder->extraJoins = [
            'weight' => [
                'core_animal_event',
                '[[core_animal_event]].[[data_collection_date]] = [[weight.data_collection_date]] AND [[weight]].[[event_type]] = 6 AND [[weight]].[[animal_id]] = [[core_animal_event]].[[animal_id]]'
            ],
        ];
        $weightFields = [
            'heartgirth' => 'HeartGirth',
            'weight_kg' => 'Weight',
            'body_score' => 'Bodyscore',
            'estimated_weight' => 'estimated weight'
        ];
        foreach ($weightFields as $weightField => $alias) {
            $field = ReportBuilder::getFullColumnName($weightField, new WeightEvent(), $alias, true, 'weight');
            $builder->extraSelectExpressions[] = new Expression($field);
        }

        return $builder;
    }

    public static function calfDataReport($filter)
    {
        $fields = [
            'region_id' => null,
            'district_id' => null,
            'ward_id' => null,
            'village_id' => null,
            'region.name' => null,
            'district.name' => null,
            'ward.code' => null,
            'village.code' => null,
            'animal.farm.id' => null,
            'animal.farm.gender_code' => null,
            'animal.farm.total_cattle_owned' => null,
            'animal.farm.total_cattle_owned_by_female' => null,
            'animal.farm.total_cattle_owned_by_male' => null,
            'animal.farm.total_cattle_owned_joint' => null,
            'animal.id' => null,
            'animal.tag_id' => null,
            'animal.birthdate' => null,
            'heartgirth' => null,
            'weight_kg' => null,
            //'estimated_weight' => null,
            'body_score' => null,
            'animal.sire_tag_id' => null,
            'animal.dam_tag_id' => null,
            'animal.sex' => null,
            'animal.main_breed' => null,
            'animal.longitude' => null,
            'animal.latitude' => null,
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
            'ward.code' => 'Wareda',
            'village.code' => 'Kebele',
            'animal.farm.id' => 'HH_ID',
            'animal.farm.gender_code' => 'FarmerGender',
            'animal.farm.total_cattle_owned' => 'Cattleowned',
            'animal.farm.total_cattle_owned_by_female' => 'total_cattle_owned_by_female',
            'animal.farm.total_cattle_owned_by_male' => 'total_cattle_owned_by_male',
            'animal.farm.total_cattle_owned_joint' => 'total_cattle_owned_joint',
            'animal.tag_id' => 'AnimalTagID',
            'animal.id' => 'AnimalID',
            'animal.birthdate' => 'Birthdate',
            'heartgirth' => 'HeartGirth',
            'weight_kg' => 'Weight',
            //'estimated_weight' => 'estimated weight',
            'body_score' => 'Bodyscore',
            'animal.sire_tag_id' => 'Sire ID',
            'animal.dam_tag_id' => 'Dam ID',
            'animal.sex' => 'Sex',
            'animal.main_breed' => 'Breed',
            'animal.longitude' => 'Longitude',
            'animal.latitude' => 'Latitude',
        ];
        # TODO: define these fields to be decoded elsewhere
        $breeds = \backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS;
        $animal_type = \backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES;
        $genders = \backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_GENDER;
        $decodedFields = [
            'animal.main_breed' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params' => [
                    //'\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS',
                    "$breeds",
                    'fieldValue', // the value of this field
                ]
            ],
            'animal.birthdate' => [
                'function' => '\common\helpers\DateUtils::formatDate',
                'params' => [
                    'fieldValue',
                    'd/m/Y',
                ]
            ],
            'animal.farm.gender_code' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params' => [
                    "$genders",
                    'fieldValue', // the value of this field
                ]
            ],
            'animal.sex' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params' => [
                    "$genders",
                    'fieldValue', // the value of this field
                ]
            ],
        ];

        $excludeFromReport = array_keys($filterValues);

        $from = ArrayHelper::getValue($filter, 'from');
        $to = ArrayHelper::getValue($filter, 'to');

        $orderBy = '';

        $builder = new ReportBuilder();
        $builder->model = 'Weights_Event';
        $builder->filterConditions = $filterConditions;
        $builder->filterValues = $filterValues;
        $builder->fieldAliases = $fieldAliases;
        $builder->excludeFromReport = $excludeFromReport;
        $builder->decodeFields = $decodedFields;
        $builder->orderBy = $orderBy;
        //$builder->limit = 50;
        $builder->country_id = $filter['country_id'] ?? null;
        $builder->name = 'Calf_Data_' . ($filter['country_id'] ? Country::getScalar('name', ['id' => $filter['country_id']]) : '');

        $animalTypes = [Animal::ANIMAL_TYPE_MALE_CALF, Animal::ANIMAL_TYPE_FEMALE_CALF];
        list($condition, $params) = DbUtils::appendInCondition('{{%animal}}.[[animal_type]]', $animalTypes);
        $expression = new Expression($condition, $params);
        $builder->extraFilterExpressions[] = $expression;
        if (!empty($from) && !empty($to)) {
            $casted_date = DbUtils::castDATE(CalvingEvent::tableName() . '.[[event_date]]');
            $condition = '(' . $casted_date . '>=:from AND ' . $casted_date . '<=:to)';
            $params = [':from' => $from, ':to' => $to];
            $expression = new Expression($condition, $params);
            $builder->extraFilterExpressions[] = $expression;
        }
        $builder->rowTransformer = '\backend\modules\reports\models\Reports::transformCalfDataRow';
        return $builder;

    }

    public static function pedigreeDataReport($filter)
    {
        $fields = [
            'region_id' => null,
            'district_id' => null,
            'ward_id' => null,
            'village_id' => null,
            'region.name' => null,
            'district.name' => null,
            'ward.code' => null,
            'village.code' => null,
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
            'ward.code' => 'Ward',
            'village.code' => 'Village',
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
                'params' => [
                    //'\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS',
                    "$breeds",
                    'fieldValue', // the value of this field
                ]
            ],
            'animal_type' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params' => [
                    //'\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS',
                    "$animal_type",
                    'fieldValue', // the value of this field
                ]
            ],
            'deformities' => [
                'function' => '\backend\modules\core\models\Animal::decodeDeformities',
                'params' => [
                    'fieldValue', // the value of this field
                ]
            ],
            'farm.gender_code' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params' => [
                    "$genders",
                    'fieldValue', // the value of this field
                ]
            ],
            'farm.farmer_is_hh_head' => [
                'function' => '\common\helpers\Utils::decodeBoolean',
                'params' => [
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
        $builder->name = 'Pedigree_' . ($filter['country_id'] ? Country::getScalar('name', ['id' => $filter['country_id']]) : '');

        if (!empty($from) && !empty($to)) {
            $casted_date = DbUtils::castDATE(Animal::tableName() . '.[[birthdate]]');
            $condition = '(' . $casted_date . '>=:from AND ' . $casted_date . '<=:to)';
            $params[':from'] = $from;
            $params[':to'] = $to;
            $expression = new Expression($condition, $params);
            $builder->extraFilterExpressions[] = $expression;
        }

        //dd($builder->rawQuery());

        return $builder;

    }

    public static function pedigreeFileDataReport($filter, $version = 1)
    {
        $fields = [
            'region_id' => null,
            'district_id' => null,
            'ward_id' => null,
            'village_id' => null,
            'region.name' => null,
            'district.name' => null,
            'ward.code' => null,
            'village.code' => null,
            //'ward.name' => null,
            //'village.name' => null,
            'animal.farm.id' => null,
//            'animal.farm.animcatowned' => null,
            'animal.id' => null,
            'animal.tag_id' => null,
            //'animal.sire_id' => null,
            //'animal.dam_id' => null,
            'animal.sire_tag_id' => null,
            'animal.dam_tag_id' => null,
            'animal.birthdate' => null,
            'animal.sex' => null,
//            'animal.animal_type' => null,
            'animal.main_breed' => null,
            'heartgirth' => null,
//            'estimated_weight' => null,
//            'body_score' => null,
//            'animal.longitude' => null,
//            'animal.latitude' => null,

            //'birthdate' => null,
            //'main_breed' => null,
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
            'ward.code' => $version == 2 ? 'Ward' :'Wareda',
            'village.code' => $version == 2 ? 'Village' :'Kebele',
            //'ward.name' => 'Ward',
            //'village.name' => 'Village',
            'animal.farm.id' => 'Farm_ID',
//            'animal.farm.animcatowned' => 'AnimalCategoriesOwned',
            'animal.id' => 'AnimalID',
            'animal.tag_id' => 'AnimalRegID',
            'animal.sire_id' => 'SireID',
            'animal.dam_id' => 'DamID',
            'animal.sire_tag_id' => 'SireRegID',
            'animal.dam_tag_id' => 'DamRegID',
            'animal.birthdate' => 'BirthDate',
            'animal.sex' => 'Sex',
//            'animal.animal_type' => 'AnimalType',
            'animal.main_breed' => 'Breed',
            'heartgirth' => 'HeartGirth',
//            'estimated_weight' => 'EstimatedWeight',
//            'body_score' => 'BodyScore',
//            'animal.longitude' => 'Longitude',
//            'animal.latitude' => 'Latitude',

            //'birthdate' => 'Birthdate',
            //'main_breed' => 'Breed',
        ];

        $breeds = \backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS;
        $animal_type = \backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES;
        $genders = \backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_GENDER;
        $decodedFields = [
            'animal.main_breed' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params'=> [
                    //'\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS',
                    "$breeds",
                    'fieldValue', // the value of this field
                ]
            ],
            'animal.animal_type' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params' => [
                    //'\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS',
                    "$animal_type",
                    'fieldValue', // the value of this field
                ]
            ],
//            /*'deformities' => [
//                'function' => '\backend\modules\core\models\Animal::decodeDeformities',
//                'params'=> [
//                    'fieldValue', // the value of this field
//                ]
//            ],
            'animal.sex' => [
                'function' => '\backend\modules\core\models\Choices::getLabel',
                'params'=> [
                    "$genders",
                    'fieldValue', // the value of this field
                ]
            ],
//            'farm.farmer_is_hh_head' => [
//                'function' => '\common\helpers\Utils::decodeBoolean',
//                'params'=> [
//                    'fieldValue', // the value of this field
//                ]
//            ],*/
            'animal.birthdate' => [
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
        $builder->model = 'Weights_Event';
        $builder->filterConditions = $filterConditions;
        $builder->filterValues = $filterValues;
        $builder->fieldAliases = $fieldAliases;
        $builder->excludeFromReport = $excludeFromReport;
        $builder->decodeFields = $decodedFields;
        $builder->orderBy = $orderBy;
        //$builder->limit = 50;
        $builder->country_id = $filter['country_id'] ?? null;
        $builder->name = 'Pedigree_File_' . ($version == 2 ? $builder->name = $builder->name . 'System_ID_' : $builder->name = $builder->name . 'Original_ID_') . ($filter['country_id'] ? Country::getScalar('name', ['id' => $filter['country_id']]) : '');

        if (!empty($from) && !empty($to)) {
            $casted_date = DbUtils::castDATE(Animal::tableName() . '.[[birthdate]]');
            $condition = '(' . $casted_date . '>=:from AND ' . $casted_date . '<=:to)';
            $params[':from'] = $from;
            $params[':to'] = $to;
            $expression = new Expression($condition, $params);
            $builder->extraFilterExpressions[] = $expression;
        }

        $builder->extraFilterExpressions[] = [
            '[[animal]].[[animal_type]]' => [3, 4],
        ];

        // add the rowTransformer
        $builder->rowTransformer = '\backend\modules\reports\models\Reports::transformPedigreeFileRow';

        return $builder;
    }
}
