<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;

/**
 * This is the model class for table "member_registration_document".
 *
 * @property int $id
 * @property int $org_id
 * @property string $document_no
 * @property int $doc_type_id
 * @property string $description
 * @property string $file_name
 * @property int $is_active
 * @property int $is_approved
 * @property string $start_date
 * @property string $renewal_date
 * @property string $created_at
 * @property int $created_by
 *
 * @property Organization $org
 * @property RegistrationDocumentType $docType
 */
class RegistrationDocument extends ActiveRecord
{
    use OrganizationDataTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_registration_document}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_id', 'document_no', 'doc_type_id', 'file_name'], 'required'],
            [['org_id', 'doc_type_id', 'is_active', 'is_approved'], 'integer'],
            [['start_date', 'renewal_date'], 'safe'],
            [['document_no'], 'string', 'max' => 128],
            [['description', 'file_name'], 'string', 'max' => 255],
            [['org_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['org_id' => 'id']],
            [['doc_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistrationDocumentType::class, 'targetAttribute' => ['doc_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_id' => 'Org ID',
            'document_no' => 'Document No',
            'doc_type_id' => 'Doc Type ID',
            'description' => 'Description',
            'file_name' => 'File Name',
            'is_active' => 'Is Active',
            'is_approved' => 'Is Approved',
            'start_date' => 'Start Date',
            'renewal_date' => 'Renewal Date',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocType()
    {
        return $this->hasOne(RegistrationDocumentType::class, ['id' => 'doc_type_id']);
    }
}
