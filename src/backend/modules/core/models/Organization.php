<?php

namespace backend\modules\core\models;

use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property int $id Unique id which is auto-generated
 * @property string $name Name of the organization e.g KLBA
 * @property int $country_id The country that the organization belong
 * @property int $is_active Whether the organization is active
 * @property string $created_at Date the record was created
 * @property int|null $created_by Id of the user who created the record
 *
 * @property OrganizationRef $country
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%organization}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['country_id', 'is_active', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganizationRef::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'country_id' => 'Country ID',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(OrganizationRef::className(), ['id' => 'country_id']);
    }
}
