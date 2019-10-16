<?php

namespace backend\modules\core\models;

use common\helpers\Utils;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use yii\base\InvalidArgumentException;
use yii\db\Expression;

/**
 * This is the model class for table "core_animal_event".
 *
 * @property int $id
 * @property int $animal_id
 * @property int $event_type
 * @property int $org_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property string $event_date
 * @property string $latitude
 * @property string $longitude
 * @property string $map_address
 * @property string $latlng
 * @property string $uuid
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property float $milkmor
 * @property float $milkeve
 * @property float $milkday
 * @property float $mlkfat
 * @property float $mlkprot
 * @property float $milklact
 * @property float $mlksmc
 * @property float $milkurea
 *
 * @property Animal $animal
 * @property AnimalEventValue[] $animalEventValues
 */
class AnimalEvent extends ActiveRecord implements ActiveSearchInterface, TableAttributeInterface
{
    use ActiveSearchTrait, OrganizationUnitDataTrait, TableAttributeTrait;

    const EVENT_TYPE_MILKING = 1;
    const EVENT_TYPE_AI = 2;
    const EVENT_TYPE_PREGNANCY_DIAGNOSIS = 3;
    const EVENT_TYPE_SYNCHRONIZATION = 4;
    const EVENT_TYPE_WEIGHTS = 5;
    const EVENT_TYPE_HEALTH = 6;

    public $animalTagId;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_animal_event}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['animal_id', 'event_type'], 'required'],
            [['animal_id', 'event_type', 'org_id', 'region_id', 'district_id', 'ward_id', 'village_id'], 'integer'],
            [['event_date'], 'date', 'format' => 'php:Y-m-d'],
            [['latitude', 'longitude'], 'number'],
            [['map_address', 'uuid'], 'string', 'max' => 255],
            [['animal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::class, 'targetAttribute' => ['animal_id' => 'id']],
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
            'animal_id' => 'Animal Tag',
            'event_type' => 'Event Type',
            'org_id' => 'Country',
            'region_id' => 'Region',
            'district_id' => 'District',
            'ward_id' => 'Ward',
            'village_id' => 'Village',
            'event_date' => 'Event Date',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'map_address' => 'Map Address',
            'latlng' => 'Latlng',
            'uuid' => 'Uuid',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'animalTagId' => 'Animal Tag Id',
        ];

        return array_merge($labels, $this->getOtherAttributeLabels());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalEventValues()
    {
        return $this->hasMany(AnimalEventValue::class, ['event_id' => 'id']);
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            'animal_id',
            'event_type',
            'org_id',
            'region_id',
            'district_id',
            'ward_id',
            'village_id',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ignoreAdditionalAttributes = true;
            $this->setLocationData();
            $this->org_id = $this->animal->org_id;
            $this->region_id = $this->animal->region_id;
            $this->district_id = $this->animal->district_id;
            $this->ward_id = $this->animal->ward_id;
            $this->village_id = $this->animal->village_id;

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->ignoreAdditionalAttributes = false;

        foreach ($this->getAttributes() as $attribute => $val) {
            if ($this->isAdditionalAttribute($attribute)) {
                $this->saveAdditionalAttributes(AnimalEventValue::class, 'event_id');
            }
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAdditionalAttributeValues(AnimalEventValue::class, 'event_id');
    }

    /**
     * @return int
     */
    public static function getDefinedTableId(): int
    {
        return ExtendableTable::TABLE_ANIMAL_EVENTS;
    }

    /**
     * @return int
     */
    public static function getDefinedType(): int
    {
        return TableAttribute::TYPE_EVENT;
    }

    /**
     * @param int $int
     * @return string
     */
    public static function decodeEventType($int): string
    {
        switch ($int) {
            case self::EVENT_TYPE_MILKING:
                return 'Milking';
            case self::EVENT_TYPE_AI:
                return 'Insemination';
            case self::EVENT_TYPE_PREGNANCY_DIAGNOSIS:
                return 'Pregnancy Diagnosis';
            case self::EVENT_TYPE_SYNCHRONIZATION:
                return 'Synchronization';
            case self::EVENT_TYPE_WEIGHTS:
                return 'Weights';
            case self::EVENT_TYPE_HEALTH:
                return 'Health';
            default:
                throw new InvalidArgumentException();
        }
    }

    /**
     * @param mixed $prompt
     * @return array
     */
    public static function eventTypeOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::EVENT_TYPE_MILKING => static::decodeEventType(self::EVENT_TYPE_MILKING),
            self::EVENT_TYPE_AI => static::decodeEventType(self::EVENT_TYPE_AI),
            self::EVENT_TYPE_PREGNANCY_DIAGNOSIS => static::decodeEventType(self::EVENT_TYPE_PREGNANCY_DIAGNOSIS),
            self::EVENT_TYPE_SYNCHRONIZATION => static::decodeEventType(self::EVENT_TYPE_SYNCHRONIZATION),
            self::EVENT_TYPE_WEIGHTS => static::decodeEventType(self::EVENT_TYPE_WEIGHTS),
            self::EVENT_TYPE_HEALTH => static::decodeEventType(self::EVENT_TYPE_HEALTH),
        ], $prompt);
    }
}
