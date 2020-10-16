<?php

namespace backend\modules\core\models;

use backend\modules\auth\models\Users;
use common\helpers\DateUtils;
use common\helpers\DbUtils;
use common\helpers\Utils;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use common\models\CustomValidationsTrait;
use Yii;

/**
 * This is the model class for table "core_animal_event".
 *
 * @property int $id
 * @property int $animal_id
 * @property int $event_type
 * @property int $country_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property int $org_id
 * @property int $client_id
 * @property string $event_date
 * @property string $data_collection_date
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
 * @property string $migration_id
 *
 * @property Animal $animal
 * @property Country $country
 * @property Users $fieldAgent
 * @property AnimalEvent $lactation
 */
class AnimalEvent extends ActiveRecord implements ActiveSearchInterface, TableAttributeInterface
{
    use ActiveSearchTrait, CountryUnitDataTrait, TableAttributeTrait, CustomValidationsTrait, AnimalEventValidators;

    const EVENT_TYPE_CALVING = 1;
    const EVENT_TYPE_MILKING = 2;
    const EVENT_TYPE_AI = 3;
    const EVENT_TYPE_PREGNANCY_DIAGNOSIS = 4;
    const EVENT_TYPE_SYNCHRONIZATION = 5;
    const EVENT_TYPE_WEIGHTS = 6;
    const EVENT_TYPE_HEALTH = 7;
    const EVENT_TYPE_EXITS = 9;
    const EVENT_TYPE_HAIR_SAMPLING = 10;//no data available yet
    const EVENT_TYPE_CERTIFICATION = 11;//no data available yet
    const EVENT_TYPE_VACCINATION = 12;
    const EVENT_TYPE_PARASITE_INFECTION = 13;
    const EVENT_TYPE_INJURY = 14;
    const EVENT_TYPE_HOOF_HEALTH = 15;
    const EVENT_TYPE_HOOF_TREATMENT = 16;


    public $animalTagId;

    const SCENARIO_MISTRO_DB_UPLOAD = 'KLBA_UPLOAD';

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
            [['animal_id', 'event_type', 'country_id', 'region_id', 'district_id', 'ward_id', 'village_id', 'field_agent_id'], 'integer'],
            [['event_date', 'data_collection_date'], 'date', 'format' => 'php:Y-m-d'],
            [['latitude', 'longitude'], 'number'],
            [['map_address', 'uuid'], 'string', 'max' => 255],
            ['event_date', 'validateNoFutureDate'],
            ['event_date', 'unique', 'targetAttribute' => ['animal_id', 'event_type', 'event_date'], 'message' => '{attribute} should be unique per animal', 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            [['org_id', 'client_id'], 'safe'],
            ['migration_id', 'unique', 'except' => self::SCENARIO_MISTRO_DB_UPLOAD],
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
            'animal_id' => 'Animal ID',
            'event_type' => 'Event Type',
            'country_id' => 'Country ID',
            'region_id' => 'Region ID',
            'district_id' => 'District ID',
            'ward_id' => 'Ward ID',
            'village_id' => 'Village ID',
            'org_id' => 'External Organization ID',
            'client_id' => 'Client ID',
            'event_date' => 'Event Date',
            'data_collection_date' => 'Date',
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
            'field_agent_id' => 'Field Agent ID'
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
        $alias = static::tableName();
        return [
            'animal_id',
            'event_type',
            [$alias . '.country_id', 'country_id', '', '='],
            [$alias . '.org_id', 'org_id', '', '='],
            'region_id',
            'district_id',
            'ward_id',
            'village_id',
            'org_id',
            'client_id',
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
            $this->ignoreAdditionalAttributes = false;
            $this->setLocationData();
            $this->country_id = $this->animal->country_id;
            $this->region_id = $this->animal->region_id;
            $this->district_id = $this->animal->district_id;
            $this->ward_id = $this->animal->ward_id;
            $this->village_id = $this->animal->village_id;
            $this->org_id = $this->animal->org_id;
            $this->client_id = $this->animal->client_id;
            if (empty($this->data_collection_date)) {
                $this->data_collection_date = $this->event_date;
            }
            if (empty($this->event_date)) {
                $this->event_date = $this->data_collection_date;
            }
            if ($this->event_type == self::EVENT_TYPE_MILKING) {
                if (empty($this->milkday)) {
                    $this->milkday = ((float)$this->milkmor + (float)$this->milkeve + (float)$this->milkmid);
                }
                $this->setDIM();
            }
            $this->setAdditionalAttributesValues();
            $this->setLactationId();

            return true;
        }
        return false;
    }

    protected function setDIM()
    {
        if ($this->event_type != self::EVENT_TYPE_MILKING || null === $this->lactation || empty($this->lactation->event_date) || empty($this->event_date)) {
            return;
        }
        if (!empty($this->dim)) {
            return;
        }
        $diff = DateUtils::getDateDiff($this->lactation->event_date, $this->event_date);
        $this->dim = $diff->days;
    }

    /**
     * @param int $animalId
     * @param int $lactationId
     * @throws \yii\db\Exception
     */
    public static function setTestDayNo($animalId, $lactationId)
    {
        list($sql, $params) = static::getTestDayNoUpdateSql($animalId, $lactationId, 1);
        if (!empty($sql)) {
            Yii::$app->db->createCommand($sql, $params)->execute();
        }
    }

    /**
     * @param int $animalId
     * @param int $lactationId
     * @param int $i
     * @return array
     * @throws \Exception
     */
    public static function getTestDayNoUpdateSql($animalId, $lactationId, $i = 1)
    {
        $data = static::getData(['id'], ['event_type' => self::EVENT_TYPE_MILKING, 'animal_id' => $animalId, 'lactation_id' => $lactationId], [], ['orderBy' => ['event_date' => SORT_ASC]]);
        $n = 1;
        $sql = "";
        $params = [];
        $table = static::tableName();
        foreach ($data as $row) {
            $sql .= "UPDATE {$table} SET [[testday_no]]=:tdno{$n}{$i} WHERE [[id]]=:id{$n}{$i};";
            $params[":tdno{$n}{$i}"] = $n;
            $params[":id{$n}{$i}"] = $row['id'];
            $n++;
        }
        return [$sql, $params];
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
        if ($this->event_type != self::EVENT_TYPE_MILKING || !empty($this->lactation_id)) {
            return;
        }
        $this->lactation_id = static::fetchLactationId($this->animal_id, $this->event_date);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->event_type == self::EVENT_TYPE_CALVING) {
            if ($this->scenario != self::SCENARIO_MISTRO_DB_UPLOAD) {
                if ($insert) {
                    static::setLactationNumber($this->animal_id);
                }
                //update milk records
                /*$data = static::getData(['id', 'event_date'], ['event_type' => self::EVENT_TYPE_MILKING, 'animal_id' => $this->animal_id]);
                foreach ($data as $row) {
                    $lactation_id = static::fetchLactationId($this->animal_id, $row['event_date']);
                    static::updateAll(['lactation_id' => $lactation_id], ['id' => $row['id']]);
                }*/
            }
        }
        if ($this->event_type == self::EVENT_TYPE_MILKING) {
            if (!empty($this->lactation_id) && $this->scenario != self::SCENARIO_MISTRO_DB_UPLOAD) {
                if ($insert) {
                    static::setTestDayNo($this->animal_id, $this->lactation_id);
                }
            }
        }
    }

    public static function setLactationNumber($animalId)
    {
        list($sql, $params) = static::getLactationNumberUpdateSql($animalId, 1);
        if (!empty($sql)) {
            Yii::$app->db->createCommand($sql, $params)->execute();
        }
    }

    /**
     * @param int $animalId
     * @param int $i
     * @return array
     * @throws \Exception
     */
    public static function getLactationNumberUpdateSql($animalId, $i = 1)
    {
        $data = static::getData('id', ['event_type' => self::EVENT_TYPE_CALVING, 'animal_id' => $animalId], [], ['orderBy' => ['event_date' => SORT_ASC]]);
        $n = 1;
        $params = [];
        $sql = "";
        $table = static::tableName();
        foreach ($data as $row) {
            $sql .= "UPDATE {$table} SET [[lactation_number]]=:lact{$n}{$i} WHERE [[id]]=:id{$n}{$i};";
            $params[":lact{$n}{$i}"] = $n;
            $params[":id{$n}{$i}"] = $row['id'];
            $n++;
        }
        return [$sql, $params];
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
        return TableAttribute::TABLE_ANIMAL_EVENT;
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
                return 'Artificial Insemination';
            case self::EVENT_TYPE_PREGNANCY_DIAGNOSIS:
                return 'Pregnancy Diagnosis';
            case self::EVENT_TYPE_SYNCHRONIZATION:
                return 'Synchronization';
            case self::EVENT_TYPE_WEIGHTS:
                return 'Weight/Growth/Feed';
            case self::EVENT_TYPE_HEALTH:
                return 'Health';
            case self::EVENT_TYPE_EXITS:
                return 'Exits';
            case self::EVENT_TYPE_HAIR_SAMPLING:
                return 'Hair Sampling';
            case self::EVENT_TYPE_CERTIFICATION:
                return 'Certification';
            case self::EVENT_TYPE_VACCINATION:
                return 'Vaccination';
            case self::EVENT_TYPE_PARASITE_INFECTION:
                return 'Parasite Infection';
            case self::EVENT_TYPE_INJURY:
                return 'Injury';
            case self::EVENT_TYPE_HOOF_HEALTH:
                return 'Hoof Health';
            case self::EVENT_TYPE_HOOF_TREATMENT:
                return 'Hoof Treatment';
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
            self::EVENT_TYPE_VACCINATION => static::decodeEventType(self::EVENT_TYPE_VACCINATION),
            self::EVENT_TYPE_PARASITE_INFECTION => static::decodeEventType(self::EVENT_TYPE_PARASITE_INFECTION),
            self::EVENT_TYPE_INJURY => static::decodeEventType(self::EVENT_TYPE_INJURY),
            self::EVENT_TYPE_HOOF_HEALTH => static::decodeEventType(self::EVENT_TYPE_HOOF_HEALTH),
            self::EVENT_TYPE_HOOF_TREATMENT => static::decodeEventType(self::EVENT_TYPE_HOOF_TREATMENT),
            self::EVENT_TYPE_EXITS => static::decodeEventType(self::EVENT_TYPE_EXITS),
            self::EVENT_TYPE_HAIR_SAMPLING => static::decodeEventType(self::EVENT_TYPE_HAIR_SAMPLING),
            //self::EVENT_TYPE_CERTIFICATION => static::decodeEventType(self::EVENT_TYPE_CERTIFICATION),
        ], $prompt);
    }

    /**
     * @param $animalId
     * @param $eventType
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getLastAnimalEvent($animalId, $eventType)
    {
        return static::find()->andWhere(['animal_id' => $animalId, 'event_type' => $eventType])->orderBy(['event_date' => SORT_DESC])->one();

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

    /**
     * @return array
     * @throws \Exception
     */
    public function reportBuilderFields()
    {
        $this->ignoreAdditionalAttributes = true;
        $attributes = $this->attributes();
        $attrs = [];
        $fields = TableAttribute::getData(['attribute_key'], ['table_id' => self::getDefinedTableId(), 'event_type' => $this->getEventType()]);

        foreach ($fields as $k => $field) {
            $attrs[] = $field['attribute_key'];
        }
        $attrs = array_merge($attributes, $attrs);
        $unwanted = array_merge($this->reportBuilderUnwantedFields(), $this->reportBuilderAdditionalUnwantedFields());
        $attrs = array_diff($attrs, $unwanted);
        sort($attrs);
        return $attrs;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderFieldsMapping(): array
    {
        return [
            'event_type' => [
                'tooltip' => function ($field) {
                    $choices = static::eventTypeOptions();
                    return static::buildChoicesTooltip(null, $choices);
                },
            ],
            'event_date' => [
                'type' => TableAttribute::INPUT_TYPE_DATE,
            ]

        ];
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderRelations()
    {
        return array_merge(['animal'], $this->reportBuilderCommonRelations(), $this->reportBuilderCoreDataRelations());
    }

    public function getEventType(): int
    {
        return 1;
    }
}
