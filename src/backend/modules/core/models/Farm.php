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
 * @property int $total_cattle
 * @property int $field_agent_id
 * @property string $field_agent_name
 * @property string $project
 * @property string $farm_type
 * @property string $gender_code
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
class Farm extends ActiveRecord implements ActiveSearchInterface, UploadExcelInterface
{
    use ActiveSearchTrait, OrganizationUnitDataTrait;

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
            [['name', 'org_id', 'region_id', 'district_id', 'ward_id' ], 'required'],
            [['org_id', 'region_id', 'district_id', 'ward_id', 'village_id', 'total_cattle', 'field_agent_id', 'is_active',], 'integer'],
            [['reg_date'], 'date', 'format' => 'Y-m-d'],
            [['latitude', 'latitude'], 'number'],
            [['code', 'name', 'project', 'field_agent_name', 'farmer_name'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 20],
            [['email', 'map_address'], 'string', 'max' => 255],
            [['farm_type'], 'string', 'max' => 30],
            [['gender_code'], 'string', 'max' => 10],
            [['email'], 'email'],
            [['org_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['org_id' => 'id']],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
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
            'total_cattle' => 'Total Cattle Owned',
            'field_agent_id' => 'Field Agent',
            'field_agent_name' => 'AI Tech',
            'project' => 'Project',
            'farm_type' => 'Farm Type',
            'gender_code' => 'Farmer Gender',
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
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->latitude) && !empty($this->longitude)) {
                $this->latlng = new Expression("ST_GeomFromText('POINT({$this->latitude} {$this->longitude})')");
            }
            if (empty($this->farmer_name)) {
                $this->farmer_name = $this->name;
            }

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $this->addClient();
        }
    }

    protected function addClient()
    {
        $model = new Client([
            'farm_id' => $this->id,
            'name' => $this->farmer_name,
            'org_id' => $this->org_id,
            'region_id' => $this->region_id,
            'district_id' => $this->district_id,
            'ward_id' => $this->ward_id,
            'village_id' => $this->village_id,
            'phone' => $this->phone,
            'email' => $this->email,
            'field_agent_id' => $this->field_agent_id,
            'is_head' => 1,
            'gender_code' => $this->gender_code,
        ]);
        $model->enableAuditTrail = false;
        $model->save();
    }


    /**
     * @return array
     */
    public function getExcelColumns()
    {
        $columns = [
            'code',
            'reg_date',
            'region',
            'district',
            'ward',
            'village',
            'field_agent_name',
            'name',
            'phone',
            'total_cattle',
            'latitude',
            'longitude',
            'farm_type',
            'gender_code',
            'project',
        ];

        //@todo add dynamically defined fields

        return $columns;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeValues()
    {
        return $this->hasMany(FarmAttributeValue::class, ['farm_id' => 'id']);
    }

    public function getAdditionalAttributeValues()
    {
        $attributes = TableAttribute::getTableAttributes(ExtendableTable::TABLE_FARM);
        return $attributes;
    }

    public function setAdditionalProperties()
    {
        if($this->canSetProperty('name')){
            //$this->canSetProperty()
        }
        //$foo->createProperty('hello', 'something');
    }
}
