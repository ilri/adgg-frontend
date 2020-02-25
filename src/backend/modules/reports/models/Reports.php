<?php

namespace backend\modules\reports\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
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

    public static function milkDataReport($filter){

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
