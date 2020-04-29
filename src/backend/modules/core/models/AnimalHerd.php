<?php

namespace backend\modules\core\models;

use common\excel\ImportActiveRecordInterface;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_animal_herd".
 *
 * @property int $id
 * @property string $name
 * @property int $farm_id
 * @property string $uuid
 * @property int $country_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property int $org_id
 * @property int $client_id
 * @property float $latitude
 * @property float $longitude
 * @property string $map_address
 * @property string $latlng
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property string $reg_date
 * @property string $project
 * @property string $additional_attributes
 * @property string $migration_id
 *
 * @property Farm $farm
 * @property Country $country
 */
class AnimalHerd extends ActiveRecord implements ActiveSearchInterface, ImportActiveRecordInterface, TableAttributeInterface
{
    use ActiveSearchTrait, CountryUnitDataTrait, TableAttributeTrait;

    public $farmerName;
    public $farmerPhone;
    public $farmerEmail;


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
            [['farm_id', 'name'], 'required'],
            [['farm_id', 'country_id', 'region_id', 'district_id', 'ward_id', 'village_id'], 'integer'],
            [['name', 'map_address'], 'string', 'max' => 255],
            [['project'], 'string', 'max' => 128],
            [['latitude', 'longitude'], 'number'],
            [['farm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Farm::class, 'targetAttribute' => ['farm_id' => 'id']],
            [['reg_date'], 'date', 'format' => 'php:Y-m-d'],
            //[['name'], 'unique', 'targetAttribute' => ['farm_id', 'name'], 'message' => '{attribute} already exists.'],
            [$this->getExcelColumns(), 'safe', 'on' => self::SCENARIO_UPLOAD],
            [['additional_attributes', 'org_id', 'client_id'], 'safe'],
            [$this->getAdditionalAttributes(), 'safe'],
            ['migration_id', 'unique'],
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
            'name' => 'Herd Name',
            'farm_id' => 'Farm',
            'uuid' => 'Uuid',
            'country_id' => 'Country',
            'region_id' => 'Region',
            'district_id' => 'District',
            'ward_id' => 'Ward',
            'village_id' => 'Village',
            'org_id' => 'Organization',
            'client_id' => 'Client ',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'map_address' => 'Map Address',
            'latlng' => 'Latlng',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'reg_date' => 'Registration date',
            'project' => 'Project',
            'farmerName' => 'Farmer Name',
            'farmerPhone' => 'Farmer Phone',
            'farmerEmail' => 'Farmer Email',
        ];
        return array_merge($labels, $this->getOtherAttributeLabels());
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
            ['project', 'project'],
            'country_id',
            'region_id',
            'district_id',
            'ward_id',
            'village_id',
            'org_id',
            'client_id',
            'farm_id',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ignoreAdditionalAttributes = true;
            $this->setLocationData();
            $this->country_id = $this->farm->country_id;
            $this->region_id = $this->farm->region_id;
            $this->district_id = $this->farm->district_id;
            $this->ward_id = $this->farm->ward_id;
            $this->village_id = $this->farm->village_id;
            $this->org_id = $this->farm->org_id;
            $this->client_id = $this->farm->client_id;

            $this->setAdditionalAttributesValues();

            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAdditionalAttributeValues();
    }


    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'name',
            'reg_date',
            'farmerName',
            'farmerPhone',
            'farmerEmail',
            'project',
            'latitude',
            'longitude',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getDefinedTableId(): int
    {
        return ExtendableTable::TABLE_HERDS;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderRelations()
    {
        return array_merge(['farm'], $this->reportBuilderCommonRelations(), $this->reportBuilderCoreDataRelations());
    }
}
