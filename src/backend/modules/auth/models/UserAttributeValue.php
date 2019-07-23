<?php

namespace backend\modules\auth\models;

use backend\modules\core\models\TableAttribute;
use backend\modules\core\models\TableAttributeValueTrait;
use common\models\ActiveRecord;

/**
 * This is the model class for table "auth_user_attribute_value".
 *
 * @property int $id
 * @property int $user_id
 * @property int $attribute_id
 * @property string $attribute_value
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Users $user
 * @property TableAttribute $tableAttribute
 */
class UserAttributeValue extends ActiveRecord
{
    use TableAttributeValueTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%auth_user_attribute_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'attribute_id', 'attribute_value'], 'required'],
            [['user_id', 'attribute_id'], 'integer'],
            [['attribute_value'], 'string', 'max' => 1000],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => 'User ID',
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
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }
}
