<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use yii\db\Expression;
use yii\helpers\Html;

/**
 * This is the model class for table "core_farm".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $org_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property string $reg_date
 * @property string $farmer_name
 * @property string $phone
 * @property string $email
 * @property int $field_agent_id
 * @property string $field_agent_name
 * @property string $project
 * @property string $farm_type
 * @property string $gender_code
 * @property int $farmer_is_hh_head
 * @property int $is_active
 * @property string $latitude
 * @property string $longitude
 * @property string $map_address
 * @property string $latlng
 * @property string $uuid
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property int $is_deleted
 * @property string $deleted_at
 * @property int $deleted_by
 *
 * @property Organization $org
 * @property FarmAttributeValue[] $attributeValues
 */
class Farm extends ActiveRecord implements ActiveSearchInterface, UploadExcelInterface, TableAttributeInterface
{
    use ActiveSearchTrait, OrganizationUnitDataTrait, TableAttributeTrait;

    const SCENARIO_UPLOAD = 'upload';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_farm}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['farmer_name', 'org_id', 'region_id', 'district_id', 'ward_id'], 'required'],
            [['name'], 'required', 'except' => [self::SCENARIO_UPLOAD]],
            [['org_id', 'region_id', 'district_id', 'ward_id', 'village_id', 'field_agent_id', 'is_active', 'farmer_is_hh_head'], 'safe'],
            [['latitude', 'latitude'], 'number'],
            [['code', 'name', 'project', 'field_agent_name', 'farmer_name'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 20, 'except' => [self::SCENARIO_UPLOAD]],
            [['email', 'map_address'], 'string', 'max' => 255],
            [['farm_type'], 'string', 'max' => 30],
            [['gender_code'], 'string', 'max' => 10],
            [['reg_date'], 'date', 'format' => 'Y-m-d', 'except' => self::SCENARIO_UPLOAD],
            [$this->getAdditionalAttributes(), 'safe'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Farm Name',
            'org_id' => 'Country',
            'region_id' => $this->org !== null ? Html::encode($this->org->unit1_name) : 'Region',
            'district_id' => $this->org !== null ? Html::encode($this->org->unit2_name) : 'District',
            'ward_id' => $this->org !== null ? Html::encode($this->org->unit3_name) : 'Ward',
            'village_id' => $this->org !== null ? Html::encode($this->org->unit4_name) : 'Village',
            'farmer_name' => 'Farmer Name',
            'reg_date' => 'Reg Date',
            'phone' => 'Farmer Phone No.',
            'email' => 'Farmer Email',
            'field_agent_id' => 'AITech/PRA Id',
            'field_agent_name' => 'AITech/PRA Name',
            'field_agent_code' => 'AITech/PRA Code',
            'project' => 'Project',
            'farm_type' => 'Farm Type',
            'gender_code' => 'Farmer Gender',
            'farmer_is_hh_head' => 'Farmer Is Household Head',
            'is_active' => 'Active',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'map_address' => 'Map Address',
            'latlng' => 'Latlong',
            'uuid' => 'Uuid',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];

        return array_merge($labels, $this->getOtherAttributeLabels());
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['code', 'code'],
            ['name', 'name'],
            ['phone', 'phone'],
            ['project', 'project'],
            ['farmer_name', 'farmer_name'],
            'org_id',
            'region_id',
            'district_id',
            'ward_id',
            'village_id',
            'field_agent_id',
            'farm_type',
            'gender_code',
            'is_active',
            'farmer_is_hh_head',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ignoreAdditionalAttributes = true;
            if (null === $this->farmer_is_hh_head) {
                $this->farmer_is_hh_head = 0;
            }
            if (!empty($this->latitude) && !empty($this->longitude)) {
                $this->latlng = new Expression("ST_GeomFromText('POINT({$this->latitude} {$this->longitude})')");
            }
            if (empty($this->name)) {
                $this->name = $this->farmer_name;
            }

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->saveAdditionalAttributes(FarmAttributeValue::class, 'farm_id');
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAdditionalAttributeValues(FarmAttributeValue::class, 'farm_id');
    }

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        $columns = [
            'code',
            'name',
            'reg_date',
            'region_code',
            'district_code',
            'ward_code',
            'village_code',
            'field_agent_name',
            'farmer_name',
            'phone',
            'email',
            'gender_code',
            'farmer_is_hh_head',
            'field_agent_code',
            'field_agent_name',
            'project',
            'farm_type',
            'latitude',
            'longitude',
        ];

        $columns = array_merge($columns, $this->getAdditionalAttributes());

        return [
            'survey_id',
            'start_time',
            'end_time',
            'device_id',
            'reg_date',
            'field_agent_code',
            'region_code',
            'district_code',
            'ward_code',
            'village_code',
            'farmer_name',
            'code',
            'phone',
            'gender_code',
            'farmer_age',
            'farmer_age_range',
            'farmer_is_hh_head',
            'farmer_relationship_to_hhh',
            'farmer_relationship_to_hhh_other',
            'hhh_name',
            'hhh_mobile',
            'hhh_gender',
            'hhh_age',
            'hhh_age_range',
            'nmale0to5',
            'nfemale0to5',
            'nmale6to14',
            'nfemale6to14',
            'nmale15to64',
            'nfemale15to64',
            'nmaleo64',
            'nfemaleo64',
            'c_parcelno',
            'livestock_in_hh',
            'total_cattle_owned',
            'total_cattle_owned_by_male',
            'total_cattle_owned_by_female',
            'total_cattle_owned_joint',
            'animcatowned',
            'hhproblems',
            'hhproblems_other',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeValues()
    {
        return $this->hasMany(FarmAttributeValue::class, ['farm_id' => 'id']);
    }

    /**
     * @return int
     */
    public static function getDefinedTableId(): int
    {
        return ExtendableTable::TABLE_FARM;
    }

    /**
     * @return int
     */
    public static function getDefinedType(): int
    {
        return TableAttribute::TYPE_ATTRIBUTE;
    }
}
