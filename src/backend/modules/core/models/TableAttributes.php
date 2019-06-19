<?php

namespace backend\modules\core\models;

use Yii;

/**
 * This is the model class for table "core_table_attributes".
 *
 * @property int $id
 * @property string $attribute_key
 * @property string $attribute_label
 * @property int $table_id
 * @property int $group_id
 * @property int $data_type
 * @property int $input_type
 * @property string $default_value
 * @property int $is_active
 * @property string $created_at
 * @property int $created_by
 *
 * @property CoreTableAttributesGroup $group
 */
class TableAttributes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'core_table_attributes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_key', 'attribute_label', 'table_id', 'data_type', 'input_type'], 'required'],
            [['table_id', 'group_id', 'data_type', 'input_type', 'is_active', 'created_by'], 'integer'],
            [['default_value'], 'string'],
            [['created_at'], 'safe'],
            [['attribute_key'], 'string', 'max' => 128],
            [['attribute_label'], 'string', 'max' => 255],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoreTableAttributesGroup::className(), 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attribute_key' => 'Attribute Key',
            'attribute_label' => 'Attribute Label',
            'table_id' => 'Table ID',
            'group_id' => 'Group ID',
            'data_type' => 'Data Type',
            'input_type' => 'Input Type',
            'default_value' => 'Default Value',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(CoreTableAttributesGroup::className(), ['id' => 'group_id']);
    }
}
