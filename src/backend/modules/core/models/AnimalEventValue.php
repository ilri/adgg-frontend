<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;

/**
 * This is the model class for table "core_animal_event_value".
 *
 * @property int $id
 * @property int $animal_id
 * @property int $event_id
 * @property int $attribute_id
 * @property string $attribute_value
 * @property array $attribute_value_json
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property AnimalEvent $event
 */
class AnimalEventValue extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_animal_event_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'attribute_id', 'attribute_value'], 'required'],
            [['animal_id', 'event_id', 'attribute_id'], 'integer'],
            [['attribute_value'], 'string', 'max' => 1000],
            [['attribute_value_json'], 'safe'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => AnimalEvent::class, 'targetAttribute' => ['event_id' => 'id']],
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
            'event_id' => 'Event ID',
            'attribute_id' => 'Attribute ID',
            'attribute_value' => 'Attribute Value',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(AnimalEvent::class, ['id' => 'event_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->animal_id = $this->event->animal_id;
            }
            return true;
        }
        return false;
    }


}