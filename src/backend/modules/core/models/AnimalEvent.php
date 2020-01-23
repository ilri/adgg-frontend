<?php

namespace backend\modules\core\models;

use backend\modules\auth\models\Users;
use common\helpers\DbUtils;
use common\helpers\Utils;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use common\models\CustomValidationsTrait;

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
 * @property int $field_agent_id
 * @property int $lactation_id
 * @property string $lactation_number
 * @property string|array $additional_attributes
 *
 * @property Animal $animal
 * @property Users $fieldAgent
 * @property AnimalEvent $lactation
 * @property
 */
class AnimalEvent extends ActiveRecord implements ActiveSearchInterface, TableAttributeInterface
{
    use ActiveSearchTrait, OrganizationUnitDataTrait, TableAttributeTrait, CustomValidationsTrait, AnimalEventValidators;

    const EVENT_TYPE_CALVING = 1;
    const EVENT_TYPE_MILKING = 2;
    const EVENT_TYPE_AI = 3;
    const EVENT_TYPE_PREGNANCY_DIAGNOSIS = 4;
    const EVENT_TYPE_SYNCHRONIZATION = 5;
    const EVENT_TYPE_WEIGHTS = 6;
    const EVENT_TYPE_HEALTH = 7;
    const EVENT_TYPE_FEEDING = 8;
    const EVENT_TYPE_EXITS = 9;
    const EVENT_TYPE_SAMPLING = 10;//no data available yet
    const EVENT_TYPE_CERTIFICATION = 11;//no data available yet

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
            [['animal_id', 'event_type', 'event_date'], 'required'],
            [['animal_id', 'event_type', 'org_id', 'region_id', 'district_id', 'ward_id', 'village_id', 'field_agent_id'], 'integer'],
            [['event_date'], 'date', 'format' => 'php:Y-m-d'],
            [['latitude', 'longitude'], 'number'],
            [['map_address', 'uuid'], 'string', 'max' => 255],
            ['event_date', 'validateNoFutureDate'],
            ['event_date', 'unique', 'targetAttribute' => ['org_id', 'animal_id', 'event_type', 'event_date'], 'message' => '{attribute} should be unique per animal'],
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
            'animal_id' => 'Animal',
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
            'field_agent_id' => 'Field Agent'
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
    public function getFieldAgent()
    {
        return $this->hasOne(Users::class, ['id' => 'field_agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLactation()
    {
        return $this->hasOne(AnimalEvent::class, ['id' => 'lactation_id']);
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
            'field_agent_id',
        ];
    }

    public function fields()
    {
        $fields = $this->apiResourceFields();
        $fields['event_type'] = function () {
            return static::decodeEventType($this->event_type);
        };
        return $fields;
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
            $this->setAdditionalAttributesValues();
            $this->setLactationId();
            return true;
        }
        return false;
    }

    /**
     * @param string $animalId
     * @param string $milkDate
     * @return mixed|null
     * @throws \Exception
     */
    public static function fetchLactationId($animalId, $milkDate)
    {
        $condition = '[[event_date]]<=:event_date';//calving date must be less than or equal to milking date
        $params = [':event_date' => $milkDate];
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_CALVING, $condition, $params);
        list($condition, $params) = DbUtils::appendCondition('animal_id', $animalId, $condition, $params);
        $data = static::getData(['id'], $condition, $params, ['orderBy' => ['event_date' => SORT_DESC], 'limit' => 1]);
        if (empty($data)) {
            return null;
        }
        return $data[0]['id'] ?? null;
    }

    protected function setLactationId()
    {
        //only done for milking event
        if ($this->event_type != self::EVENT_TYPE_MILKING) {
            return;
        }
        $this->lactation_id = static::fetchLactationId($this->animal_id, $this->event_date);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->setLactationNumber();
        if ($this->event_type == self::EVENT_TYPE_CALVING) {
            //update milk records
            $data = static::getData(['id', 'event_date'], ['event_type' => self::EVENT_TYPE_MILKING, 'animal_id' => $this->animal_id]);
            foreach ($data as $row) {
                $lactation_id = static::fetchLactationId($this->animal_id, $row['event_date']);
                static::updateAll(['lactation_id' => $lactation_id], ['id' => $row['id']]);
            }
        }
    }

    protected function setLactationNumber()
    {
        //only done for calving/lactation event
        if ($this->event_type != self::EVENT_TYPE_CALVING) {
            return;
        }
        $data = static::getData('id', ['event_type' => self::EVENT_TYPE_CALVING, 'animal_id' => $this->animal_id], [], ['orderBy' => ['event_date' => SORT_ASC]]);
        $n = 1;
        foreach ($data as $row) {
            static::updateAll(['lactation_number' => $n], ['id' => $row['id']]);
            $n++;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAdditionalAttributeValues();
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
            case self::EVENT_TYPE_CALVING:
                return 'Calving';
            case self::EVENT_TYPE_MILKING:
                return 'Milking';
            case self::EVENT_TYPE_AI:
                return 'Insemination';
            case self::EVENT_TYPE_PREGNANCY_DIAGNOSIS:
                return 'Pregnancy Diagnosis';
            case self::EVENT_TYPE_SYNCHRONIZATION:
                return 'Synchronization';
            case self::EVENT_TYPE_WEIGHTS:
                return 'Weights/Growth';
            case self::EVENT_TYPE_HEALTH:
                return 'Health';
            case self::EVENT_TYPE_FEEDING:
                return 'Feeding';
            case self::EVENT_TYPE_EXITS:
                return 'Exits';
            case self::EVENT_TYPE_SAMPLING:
                return 'Sampling';
            case self::EVENT_TYPE_CERTIFICATION:
                return 'Certification';
            default:
                return '';
        }
    }

    /**
     * @param mixed $prompt
     * @return array
     */
    public static function eventTypeOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::EVENT_TYPE_CALVING => static::decodeEventType(self::EVENT_TYPE_CALVING),
            self::EVENT_TYPE_MILKING => static::decodeEventType(self::EVENT_TYPE_MILKING),
            self::EVENT_TYPE_AI => static::decodeEventType(self::EVENT_TYPE_AI),
            self::EVENT_TYPE_PREGNANCY_DIAGNOSIS => static::decodeEventType(self::EVENT_TYPE_PREGNANCY_DIAGNOSIS),
            self::EVENT_TYPE_SYNCHRONIZATION => static::decodeEventType(self::EVENT_TYPE_SYNCHRONIZATION),
            self::EVENT_TYPE_WEIGHTS => static::decodeEventType(self::EVENT_TYPE_WEIGHTS),
            self::EVENT_TYPE_HEALTH => static::decodeEventType(self::EVENT_TYPE_HEALTH),
            self::EVENT_TYPE_FEEDING => static::decodeEventType(self::EVENT_TYPE_FEEDING),
            self::EVENT_TYPE_EXITS => static::decodeEventType(self::EVENT_TYPE_EXITS),
            //self::EVENT_TYPE_SAMPLING => static::decodeEventType(self::EVENT_TYPE_SAMPLING),
            //self::EVENT_TYPE_CERTIFICATION => static::decodeEventType(self::EVENT_TYPE_CERTIFICATION),
        ], $prompt);
    }

    /**
     * @param int $animalId
     * @param int $eventType
     * @return bool
     */
    public static function getEventLastDate($animalId, $eventType)
    {
        $models = static::find()->andWhere(['animal_id' => $animalId, 'event_type' => $eventType])->orderBy(['id' => SORT_DESC])->all();
        $dates = [];
        foreach ($models as $model) {
            $dates[] = strtotime($model->event_date);
        }

        if ($dates == null) {
            return 'None';
        }
        $latestDate = max($dates);
        return date('d/m/Y', $latestDate);
    }

    /**
     * @param int $animalId
     * @param int $eventType
     * @return bool
     */
    public static function getEventEarlyDate($animalId, $eventType)
    {
        $models = static::find()->andWhere(['animal_id' => $animalId, 'event_type' => $eventType])->orderBy(['id' => SORT_DESC])->all();
        $dates = [];
        foreach ($models as $model) {
            $dates[] = strtotime($model->event_date);
        }
        if ($dates == null) {
            return 'None';
        }
        $earliestDate = min($dates);
        return date('d/m/Y', $earliestDate);
    }

    /**
     * @param integer $durationType
     * @param bool|string $sum
     * @param array $filters array key=>$value pair where key is the attribute name and value is the attribute value
     * @param string $dateField
     * @param null|string $from
     * @param null|string $to
     * @param mixed $condition
     * @param array $params
     * @return int
     * @throws \Exception
     */
    public static function getDashboardStats($durationType, $sum = false, $filters = [], $dateField = 'event_date', $from = null, $to = null, $condition = '', $params = [])
    {
        list($condition, $params) = static::appendOrgSessionIdCondition($condition, $params, false);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }
}
