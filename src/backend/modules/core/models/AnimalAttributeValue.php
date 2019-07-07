<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;

/**
 * This is the model class for table "core_animal_attribute_value".
 *
 * @property int $id
 * @property int $animal_id
 * @property int $attribute_id
 * @property string $attribute_value
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Animal $animal
 */
class AnimalAttributeValue extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_animal_attribute_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['animal_id', 'attribute_id', 'attribute_value'], 'required'],
            [['animal_id', 'attribute_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['attribute_value'], 'string', 'max' => 1000],
            [['animal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::class, 'targetAttribute' => ['animal_id' => 'id']],
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
    public function getAnimal()
    {
        return $this->hasOne(Animal::class, ['id' => 'animal_id']);
    }
}