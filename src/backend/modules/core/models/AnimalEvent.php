<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
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
 * @property Animal $animal
 * @property AnimalEventValue[] $animalEventValues
 */
class AnimalEvent extends ActiveRecord implements ActiveSearchInterface, TableAttributeInterface
{
    use ActiveSearchTrait, OrganizationUnitDataTrait, TableAttributeTrait;

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
            [['event_date'], 'date', 'format' => 'Y-m-d'],
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
        return [
            'id' => 'ID',
            'animal_id' => 'Animal ID',
            'event_type' => 'Event Type',
            'org_id' => 'Org ID',
            'region_id' => 'Region ID',
            'district_id' => 'District ID',
            'ward_id' => 'Ward ID',
            'village_id' => 'Village ID',
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
        ];
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
            if (!empty($this->latitude) && !empty($this->longitude)) {
                $this->latlng = new Expression("ST_GeomFromText('POINT({$this->latitude} {$this->longitude})')");
            }
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
                $this->saveAdditionalAttributeValue($attribute);
            }
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAdditionalAttributeValues(AnimalAttributeValue::class, 'animal_id');
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
     * @param string $attribute
     * @return bool
     * @throws \Exception
     */
    public function saveAdditionalAttributeValue(string $attribute): bool
    {
        if (null === $this->{$attribute}) {
            return false;
        }
        $attributeId = TableAttribute::getAttributeId(static::getDefinedTableId(), $attribute);
        $model = AnimalEventValue::find()->andWhere(['event_id' => $this->id, 'attribute_id' => $attributeId])->one();
        if (null === $model) {
            $model = new AnimalEventValue(['event_id' => $this->id, 'attribute_id' => $attributeId]);
        }
        $model->attribute_value = $this->{$attribute};
        return $model->save(false);
    }
}
