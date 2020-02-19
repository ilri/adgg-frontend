<?php

namespace backend\modules\core\models;

use Yii;

/**
 * This is the model class for table "core_client".
 *
 * @property int $id Unique id which is auto-generated
 * @property string $name Name of the client e.g ADC
 * @property string|null $description Description of the client
 * @property int $country_id The country of the client
 * @property int|null $org_id The OrganizationRefRef of the client
 * @property int $is_active Whether the record is active
 * @property string $created_at The date the record was created
 * @property int|null $created_by Id of the user who created the records
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_client}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['country_id', 'org_id', 'is_active', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'description'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'country_id' => 'Country',
            'org_id' => 'Organization',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }
}
