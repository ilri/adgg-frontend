<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use Yii;

/**
 * This is the model class for table "core_client_attribute_value".
 *
 * @property int $id
 * @property int $client_id
 * @property int $attribute_id
 * @property string $attribute_value
 * @property array $attribute_value_json
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Client $client
 * @property TableAttribute $tableAttribute
 */
class ClientAttributeValue extends ActiveRecord
{
    use TableAttributeValueTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_client_attribute_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'attribute_id', 'attribute_value'], 'required'],
            [['client_id', 'attribute_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['attribute_value'], 'string', 'max' => 1000],
            [['attribute_value_json'], 'safe'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
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
            'client_id' => 'Client ID',
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
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }
}
