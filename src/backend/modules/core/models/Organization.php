<?php

namespace backend\modules\core\models;

use backend\modules\auth\models\Users;
use common\models\ActiveRecord;

/**
 * This is the model class for table "member_organization".
 *
 * @property int $id
 * @property string $account_no
 * @property string $name
 * @property int $type
 * @property string $applicant_name
 * @property string $applicant_phone
 * @property string $applicant_email
 * @property int $daily_customers
 * @property string $country
 * @property string $county
 * @property string $sub_county
 * @property string $street
 * @property string $postal_address
 * @property string $contact_first_name
 * @property string $contact_middle_name
 * @property string $contact_last_name
 * @property string $contact_title
 * @property string $contact_phone
 * @property string $contact_email
 * @property int $membership_status
 * @property string $application_date
 * @property int $approval_status
 * @property string $date_approved
 * @property string $approved_at
 * @property int $approved_by
 * @property string $approval_notes
 * @property string $membership_end_date
 * @property int $business_entity_type
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Users[] $users
 * @property RegistrationDocument[] $registrationDocuments
 */
class Organization extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_organization}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_no', 'name', 'type', 'country', 'contact_first_name', 'contact_last_name', 'contact_phone', 'contact_email', 'membership_status', 'approval_status'], 'required'],
            [['type', 'daily_customers', 'membership_status', 'approval_status', 'approved_by', 'business_entity_type', 'created_by', 'updated_by'], 'integer'],
            [['application_date', 'date_approved', 'approved_at', 'membership_end_date', 'created_at', 'updated_at'], 'safe'],
            [['account_no', 'applicant_name', 'contact_first_name', 'contact_middle_name', 'contact_last_name'], 'string', 'max' => 128],
            [['name', 'applicant_email', 'county', 'sub_county', 'street', 'postal_address', 'contact_email', 'approval_notes'], 'string', 'max' => 255],
            [['applicant_phone', 'contact_phone'], 'string', 'max' => 20],
            [['country'], 'string', 'max' => 3],
            [['contact_title'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_no' => 'Account No',
            'name' => 'Name',
            'type' => 'Type',
            'applicant_name' => 'Applicant Name',
            'applicant_phone' => 'Applicant Phone',
            'applicant_email' => 'Applicant Email',
            'daily_customers' => 'Daily Customers',
            'country' => 'Country',
            'county' => 'County',
            'sub_county' => 'Sub County',
            'street' => 'Street',
            'postal_address' => 'Postal Address',
            'contact_first_name' => 'Contact First Name',
            'contact_middle_name' => 'Contact Middle Name',
            'contact_last_name' => 'Contact Last Name',
            'contact_title' => 'Contact Title',
            'contact_phone' => 'Contact Phone',
            'contact_email' => 'Contact Email',
            'membership_status' => 'Membership Status',
            'application_date' => 'Application Date',
            'approval_status' => 'Approval Status',
            'date_approved' => 'Date Approved',
            'approved_at' => 'Approved At',
            'approved_by' => 'Approved By',
            'approval_notes' => 'Approval Notes',
            'membership_end_date' => 'Membership End Date',
            'business_entity_type' => 'Business Entity Type',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::class, ['org_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrationDocuments()
    {
        return $this->hasMany(RegistrationDocument::class, ['org_id' => 'id']);
    }
}
