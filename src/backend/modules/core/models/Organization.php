<?php

namespace backend\modules\core\models;

use backend\modules\auth\models\Users;
use backend\modules\auth\Session;
use backend\modules\conf\models\NumberingFormat;
use common\helpers\DateUtils;
use common\helpers\FileManager;
use common\helpers\Utils;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use Yii;
use yii\base\InvalidArgumentException;

/**
 * This is the model class for table "member_organization".
 *
 * @property int $id
 * @property string $account_no
 * @property string $name
 * @property int $business_type
 * @property string $applicant_name
 * @property string $applicant_phone
 * @property string $applicant_email
 * @property int $applicant_business_ownership_type
 * @property string $daily_customers
 * @property string $country
 * @property string $county
 * @property string $sub_county
 * @property string $street
 * @property string $town
 * @property string $postal_address
 * @property string $contact_first_name
 * @property string $contact_middle_name
 * @property string $contact_last_name
 * @property string $contact_title
 * @property string $contact_phone
 * @property string $contact_alt_phone
 * @property string $contact_email
 * @property int $status
 * @property string $application_date
 * @property int $is_approved
 * @property string $date_approved
 * @property string $approved_at
 * @property int $approved_by
 * @property string $approval_notes
 * @property string $membership_end_date
 * @property int $business_entity_type
 * @property int $is_credit_requested
 * @property int $is_supplier
 * @property int $is_member
 * @property int $account_manager_id
 * @property string $logo
 * @property string $map_address
 * @property string $map_latitude
 * @property string $map_longitude
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property string $last_order_date
 * @property string $last_login_date
 *
 * @property Users[] $users
 * @property RegistrationDocument[] $registrationDocuments
 * @property Users $accountManager
 * @property Users $approvedBy
 */
class Organization extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;
    //status
    const STATUS_PENDING_APPROVAL = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_SUSPENDED = 3;

    // business types
    const BUSINESS_TYPE_MANUFACTURER = 1;
    const BUSINESS_TYPE_DISTRIBUTOR = 2;
    const BUSINESS_TYPE_PHARMACY = 3;
    const BUSINESS_TYPE_HOSPITAL = 4;
    const BUSINESS_TYPE_CLINIC = 5;
    //business entity types
    const BUSINESS_ENTITY_TYPE_LIMITED_LIABILITY_COMPANY = 1;
    const BUSINESS_ENTITY_TYPE_PARTNERSHIP = 2;
    const BUSINESS_ENTITY_TYPE_SOLE_PROPRIETOR = 3;
    //business ownership types
    const BUSINESS_OWNERSHIP_TYPE_OWNER = 1;
    const BUSINESS_OWNERSHIP_TYPE_EMPLOYEE = 2;
    const BUSINESS_OWNERSHIP_TYPE_OTHER = 3;
    //daily customers
    const DAILY_CUSTOMERS_1_50 = '1-50';
    const DAILY_CUSTOMERS_50_100 = '50-100';
    const DAILY_CUSTOMERS_100_500 = '100-500';
    const DAILY_CUSTOMERS_500_AND_ABOVE = '500+';
    //scenarios
    const SCENARIO_APPROVE = 'approve';
    const SCENARIO_SIGN_UP = 'sign_up';
    //numbering format
    const NUMBERING_FORMAT_ID = 'organization_account_no';
    //registration source
    const REG_SOURCE_ADMIN = 1;
    const REG_SOURCE_PUBLIC_PAGE = 2;
    const REG_SOURCE_WEBSITE = 3;

    /**
     * @var string
     */
    public $tmp_logo;

    /**
     * @var string
     */
    public $verify_code;

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
            [['country', 'contact_first_name', 'contact_last_name', 'contact_phone', 'contact_email'], 'required'],
            [['business_type', 'status', 'is_approved', 'approved_by', 'business_entity_type', 'applicant_business_ownership_type', 'is_credit_requested', 'is_supplier', 'is_member', 'account_manager_id'], 'integer'],
            [['application_date', 'date_approved', 'approved_at', 'membership_end_date'], 'safe'],
            [['account_no', 'applicant_name', 'contact_first_name', 'contact_middle_name', 'contact_last_name', 'daily_customers'], 'string', 'max' => 128],
            [['name', 'applicant_email', 'county', 'sub_county', 'street', 'town', 'postal_address', 'contact_email', 'approval_notes', 'map_address'], 'string', 'max' => 255],
            [['applicant_phone', 'contact_phone', 'contact_alt_phone'], 'string', 'max' => 20],
            [['country'], 'string', 'max' => 3],
            ['name', 'unique'],
            ['account_no', 'unique'],
            [['contact_title'], 'string', 'max' => 30],
            [['date_approved', 'approval_notes'], 'required', 'on' => self::SCENARIO_APPROVE],
            [['tmp_logo', 'map_latitude', 'map_longitude'], 'safe'],
            //['verify_code', 'captcha', 'captchaAction' => '/auth/auth/captcha', 'on' => self::SCENARIO_SIGN_UP,],
            [['applicant_name', 'applicant_phone', 'applicant_business_ownership_type', 'business_entity_type'], 'required', 'on' => self::SCENARIO_SIGN_UP],
            [['applicant_email', 'contact_email'], 'email'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_no' => 'Code',
            'name' => 'Country Name',
            'business_type' => 'Business Type',
            'applicant_name' => 'Applicant Name',
            'applicant_phone' => 'Applicant Phone',
            'applicant_email' => 'Applicant Email',
            'applicant_business_ownership_type' => 'Business owner or Employee?',
            'daily_customers' => 'Daily Customers',
            'country' => 'Country',
            'county' => 'County',
            'sub_county' => 'Sub County',
            'street' => 'Street',
            'town' => 'Town/City',
            'postal_address' => 'Postal Address',
            'contact_first_name' => 'Contact First Name',
            'contact_middle_name' => 'Contact Middle Name',
            'contact_last_name' => 'Contact Last Name',
            'contact_title' => 'Contact Title',
            'contact_phone' => 'Contact Phone',
            'contact_alt_phone' => 'Contact Alt. Phone',
            'contact_email' => 'Contact Email',
            'status' => 'Status',
            'application_date' => 'Application Date',
            'is_approved' => 'Approved',
            'date_approved' => 'Date Approved',
            'approved_at' => 'Approved At',
            'approved_by' => 'Approved By',
            'approval_notes' => 'Approval Notes',
            'membership_end_date' => 'Membership End Date',
            'business_entity_type' => 'Business Entity Type',
            'is_credit_requested' => 'Requested Credit',
            'account_manager_id' => 'Relationship Manager',
            'logo' => 'Logo',
            'tmp_logo' => 'Logo',
            'map_address' => 'Map Address',
            'map_latitude' => 'Latitude',
            'map_longitude' => 'Longitude',
            'last_order_date' => 'Last Order Date',
            'last_login_date' => 'Last Login Date',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountManager()
    {
        return $this->hasOne(Users::class, ['id' => 'account_manager_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(Users::class, ['id' => 'approved_by']);
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

    /**
     *  {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['account_no', 'account_no'],
            ['name', 'name'],
            'business_type',
            'country',
            'county',
            'sub_county',
            'status',
            'is_approved',
            'is_credit_requested',
            'business_entity_type',
            'is_supplier',
            'is_member',
            'account_manager_id',
            'applicant_business_ownership_type',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->setDefaultValues();
            if ($this->scenario === self::SCENARIO_APPROVE) {
                $this->approve();
            }
            if (empty($this->account_no)) {
                $this->account_no = NumberingFormat::getNextFormattedNumber(self::NUMBERING_FORMAT_ID);
            }
            $this->setLogo();

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    protected function approve()
    {
        $this->is_approved = 1;
        $this->status = self::STATUS_ACTIVE;
        $this->approved_at = DateUtils::mysqlTimestamp();
        $this->approved_by = Session::userId();
    }

    protected function setDefaultValues()
    {
        $this->name = Country::getScalar('name', ['iso2' => $this->country]);
    }


    /**
     * @param integer $intVal
     * @return string
     */
    public static function decodeStatus($intVal)
    {
        switch ($intVal) {
            case self::STATUS_PENDING_APPROVAL:
                return 'PENDING APPROVAL';
            case self::STATUS_ACTIVE:
                return 'ACTIVE';
            case self::STATUS_SUSPENDED:
                return 'SUSPENDED';
        }
    }

    /**
     * @return string
     */
    public function getDecodedStatus()
    {
        return static::decodeStatus($this->status);
    }

    /**
     * @param bool $prompt
     * @return array
     */
    public static function statusOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::STATUS_PENDING_APPROVAL => static::decodeStatus(self::STATUS_PENDING_APPROVAL),
            self::STATUS_ACTIVE => static::decodeStatus(self::STATUS_ACTIVE),
            self::STATUS_SUSPENDED => static::decodeStatus(self::STATUS_SUSPENDED),
        ], $prompt);
    }

    /**
     * @param int $intVal
     * @return string
     */
    public static function decodeBusinessType($intVal)
    {
        switch ($intVal) {
            case self::BUSINESS_TYPE_MANUFACTURER:
                return 'MANUFACTURER';
            case self::BUSINESS_TYPE_DISTRIBUTOR:
                return 'DISTRIBUTOR';
            case self::BUSINESS_TYPE_PHARMACY:
                return 'PHARMACY';
            case self::BUSINESS_TYPE_HOSPITAL:
                return 'HOSPITAL';
            case self::BUSINESS_TYPE_CLINIC:
                return 'CLINIC';
        }
    }

    /**
     * @return string
     */
    public function getDecodedBusinessType()
    {
        return static::decodeBusinessType($this->business_type);
    }

    /**
     * @param mixed $prompt
     * @return array
     */
    public static function businessTypeOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::BUSINESS_TYPE_MANUFACTURER => static::decodeBusinessType(self::BUSINESS_TYPE_MANUFACTURER),
            self::BUSINESS_TYPE_DISTRIBUTOR => static::decodeBusinessType(self::BUSINESS_TYPE_DISTRIBUTOR),
            self::BUSINESS_TYPE_PHARMACY => static::decodeBusinessType(self::BUSINESS_TYPE_PHARMACY),
            self::BUSINESS_TYPE_HOSPITAL => static::decodeBusinessType(self::BUSINESS_TYPE_HOSPITAL),
            self::BUSINESS_TYPE_CLINIC => static::decodeBusinessType(self::BUSINESS_TYPE_CLINIC),
        ], $prompt);
    }

    /**
     * @param $intVal
     * @return string
     */
    public static function decodeBusinessEntityType($intVal)
    {
        switch ($intVal) {
            case self::BUSINESS_ENTITY_TYPE_LIMITED_LIABILITY_COMPANY:
                return 'LIMITED LIABILITY COMPANY';
            case self::BUSINESS_ENTITY_TYPE_PARTNERSHIP:
                return 'PARTNERSHIP';
            case self::BUSINESS_ENTITY_TYPE_SOLE_PROPRIETOR:
                return 'SOLE PROPRIETOR';
        }
    }

    /**
     * @return string
     */
    public function getDecodedBusinessEntityType()
    {
        return static::decodeBusinessEntityType($this->business_entity_type);
    }

    /**
     * @param mixed $prompt
     * @return array
     */
    public static function businessEntityTypeOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::BUSINESS_ENTITY_TYPE_LIMITED_LIABILITY_COMPANY => static::decodeBusinessEntityType(self::BUSINESS_ENTITY_TYPE_LIMITED_LIABILITY_COMPANY),
            self::BUSINESS_ENTITY_TYPE_PARTNERSHIP => static::decodeBusinessEntityType(self::BUSINESS_ENTITY_TYPE_PARTNERSHIP),
            self::BUSINESS_ENTITY_TYPE_SOLE_PROPRIETOR => static::decodeBusinessEntityType(self::BUSINESS_ENTITY_TYPE_SOLE_PROPRIETOR),
        ], $prompt);
    }

    /**
     * @param int $intVal
     * @return string
     */
    public static function decodeBusinessOwnershipType($intVal)
    {
        switch ($intVal) {
            case self::BUSINESS_OWNERSHIP_TYPE_OWNER:
                return 'BUSINESS OWNER';
            case self::BUSINESS_OWNERSHIP_TYPE_EMPLOYEE:
                return 'EMPLOYEE';
            case self::BUSINESS_OWNERSHIP_TYPE_OTHER:
                return 'OTHER';
        }
    }

    /**
     * @return string
     */
    public function getDecodedBusinessOwnershipType()
    {
        return static::decodeBusinessOwnershipType($this->applicant_business_ownership_type);
    }

    /**
     * @param mixed $prompt
     * @return array
     */
    public static function businessOwnershipTypeOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::BUSINESS_OWNERSHIP_TYPE_OWNER => static::decodeBusinessOwnershipType(self::BUSINESS_OWNERSHIP_TYPE_OWNER),
            self::BUSINESS_OWNERSHIP_TYPE_EMPLOYEE => static::decodeBusinessOwnershipType(self::BUSINESS_OWNERSHIP_TYPE_EMPLOYEE),
            self::BUSINESS_OWNERSHIP_TYPE_OTHER => static::decodeBusinessOwnershipType(self::BUSINESS_OWNERSHIP_TYPE_OTHER),
        ], $prompt);
    }

    /**
     * @param bool $prompt
     * @return array
     */
    public static function dailyCustomersOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::DAILY_CUSTOMERS_1_50 => self::DAILY_CUSTOMERS_1_50,
            self::DAILY_CUSTOMERS_50_100 => self::DAILY_CUSTOMERS_50_100,
            self::DAILY_CUSTOMERS_100_500 => self::DAILY_CUSTOMERS_100_500,
            self::DAILY_CUSTOMERS_500_AND_ABOVE => self::DAILY_CUSTOMERS_500_AND_ABOVE,
        ], $prompt);
    }

    /**
     * @param bool $withTitle
     * @param bool $withMiddleName
     * @return string
     */
    public function getFullContactName($withTitle = false, $withMiddleName = false)
    {
        $template = "";
        if ($withTitle) {
            $template .= "{title} ";
        }
        $template .= "{firstName} ";
        if ($withMiddleName) {
            $template .= "{middleName} ";
        }
        $template .= "{lastName}";
        return trim(strtr($template, [
            '{title}' => $this->contact_title,
            '{firstName}' => $this->contact_first_name,
            '{middleName}' => $this->contact_middle_name,
            '{lastName}' => $this->contact_last_name,
        ]));
    }

    /**
     * @return bool
     */
    public function canBeApproved(): bool
    {
        if ($this->status == self::STATUS_PENDING_APPROVAL && !$this->is_approved) {
            return true;
        }
        return false;
    }

    /**
     *
     * @return string
     */
    public function getDir()
    {
        return FileManager::createDir($this->getBaseDir() . DIRECTORY_SEPARATOR . $this->uuid);
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return FileManager::createDir(FileManager::getUploadsDir() . DIRECTORY_SEPARATOR . 'organizations');
    }

    protected function setLogo()
    {
        if (empty($this->tmp_logo))
            return false;

        $ext = $ext = pathinfo($this->tmp_logo, PATHINFO_EXTENSION);
        $file_name = 'logo.' . $ext;
        $temp_dir = dirname($this->tmp_logo);
        $new_path = $this->getDir() . DIRECTORY_SEPARATOR . $file_name;
        if (copy($this->tmp_logo, $new_path)) {
            $this->logo = $file_name;
            $this->tmp_logo = null;

            if (!empty($temp_dir)) {
                FileManager::deleteDirOrFile($temp_dir);
            }
        }
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getLogoUrl()
    {
        $file_path = $this->getLogoPath();
        if (empty($file_path)) {
            return null;
        }
        $asset = Yii::$app->getAssetManager()->publish($file_path);

        return $asset[1];
    }

    /**
     * @return null|string
     */
    public function getLogoPath()
    {
        $path = null;
        if (empty($this->logo))
            return null;

        $file = $this->getDir() . DIRECTORY_SEPARATOR . $this->logo;
        if (file_exists($file)) {
            $path = $file;
        }

        return $path;
    }
}
