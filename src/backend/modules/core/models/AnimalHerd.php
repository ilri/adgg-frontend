<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_animal_herd".
 *
 * @property int $id
 * @property string $name
 * @property string $herd_id
 * @property int $farm_id
 * @property string $uuid
 * @property int $org_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property float $latitude
 * @property float $longitude
 * @property string $map_address
 * @property string $latlng
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Farm $farm
 */
class AnimalHerd extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait, OrganizationUnitDataTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_animal_herd}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['herd_id', 'farm_id'], 'required'],
            [['farm_id', 'org_id', 'region_id', 'district_id', 'ward_id', 'village_id'], 'integer'],
            [['name', 'map_address'], 'string', 'max' => 255],
            [['herd_id'], 'string', 'max' => 128],
            [['latitude', 'longitude'], 'number'],
            [['farm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Farm::class, 'targetAttribute' => ['farm_id' => 'id']],
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
            'name' => 'Name',
            'herd_id' => 'Herd ID',
            'farm_id' => 'Farm ID',
            'uuid' => 'Uuid',
            'org_id' => 'Org ID',
            'region_id' => 'Region ID',
            'district_id' => 'District ID',
            'ward_id' => 'Ward ID',
            'village_id' => 'Village ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'map_address' => 'Map Address',
            'latlng' => 'Latlng',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFarm()
    {
        return $this->hasOne(Farm::class, ['id' => 'farm_id']);
    }

    /**
     *  {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            ['herd_id', 'herd_id'],
            'org_id',
            'region_id',
            'district_id',
            'ward_id',
            'village_id',
            'farm_id',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->setLocationData();
            $this->org_id = $this->farm->org_id;
            $this->region_id = $this->farm->region_id;
            $this->district_id = $this->farm->district_id;
            $this->ward_id = $this->farm->ward_id;
            $this->village_id = $this->farm->village_id;

            return true;
        }
        return false;
    }
}
