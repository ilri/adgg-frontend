<?php

namespace backend\modules\core\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "member_registration_document_type".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $member_types
 * @property string $business_entity_types
 * @property int $is_active
 * @property int $created_at
 * @property int $created_by
 *
 * @property RegistrationDocument[] $registrationDocuments
 */
class RegistrationDocumentType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_registration_document_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active'], 'integer'],
            [['name', 'description', 'member_types', 'business_entity_types'], 'string', 'max' => 255],
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
            'member_types' => 'Member Types',
            'business_entity_types' => 'Business Entity Types',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberRegistrationDocuments()
    {
        return $this->hasMany(RegistrationDocument::class, ['doc_type_id' => 'id']);
    }
}
