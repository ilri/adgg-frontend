<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;

/**
 * This is the model class for table "core_farm_attribute_value".
 *
 * @property int $id
 * @property int $farm_id
 * @property int $attribute_id
 * @property string $attribute_value
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Farm $farm
 * @property TableAttribute $tableAttribute
 */
class FarmAttributeValue extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_farm_attribute_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['farm_id', 'attribute_id', 'attribute_value'], 'required'],
            [['farm_id', 'attribute_id'], 'integer'],
            [['attribute_value'], 'string', 'max' => 1000],
            [['farm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Farm::class, 'targetAttribute' => ['farm_id' => 'id']],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => TableAttribute::class, 'targetAttribute' => ['attribute_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'farm_id' => 'Farm ID',
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
    public function getFarm()
    {
        return $this->hasOne(Farm::class, ['id' => 'farm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTableAttribute()
    {
        return $this->hasOne(TableAttribute::class, ['id' => 'attribute_id']);
    }
}
