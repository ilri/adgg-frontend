<?php

namespace backend\modules\auth\models;


use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\ExtendableTable;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnitDataTrait;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\TableAttributeInterface;
use backend\modules\core\models\TableAttributeTrait;
use backend\modules\core\models\UploadExcelInterface;
use common\helpers\DbUtils;
use common\helpers\FileManager;
use common\helpers\Lang;
use common\helpers\Utils;
use common\models\ActiveSearchTrait;
use common\models\ActiveSearchInterface;
use Yii;
use yii\helpers\Html;
use yii\imagine\Image;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "auth_users".
 *
 * @property integer $branch_id
 * @property int $country_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property int $org_id
 * @property int $client_id
 * @property string $odk_code
 * @property string $odk_password
 * @property string|array $additional_attributes
 *
 * @property Country $country
 * @property CountryUnits $region
 * @property CountryUnits $district
 * @property CountryUnits $ward
 * @property CountryUnits $village
 *
 */
class Users extends UserIdentity implements ActiveSearchInterface, UploadExcelInterface, TableAttributeInterface
{
    use ActiveSearchTrait, CountryUnitDataTrait, UserNotificationTrait, TableAttributeTrait;

    /**
     *
     * @var bool
     */
    public $send_email = false;
    /**
     * @var
     */
    public $tmp_profile_image;

    const UPLOADS_DIR = 'users';

    public $verifyCode;

    const SCENARIO_UPLOAD = 'upload';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->timezone)) {
            $this->timezone = SystemSettings::getDefaultTimezone();
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'username', 'level_id'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['email'], 'required', 'except' => [self::SCENARIO_UPLOAD]],
            ['email', 'email'],
            [['level_id', 'role_id', 'country_id', 'auto_generate_password', 'branch_id'], 'integer'],
            [['name', 'profile_image'], 'string', 'max' => 128],
            ['username', 'string', 'min' => 4, 'max' => 30],
            // password field is required on 'create' scenario
            [
                ['password', 'confirm'],
                'required',
                'on' => [
                    self::SCENARIO_CREATE,
                    self::SCENARIO_CHANGE_PASSWORD,
                    self::SCENARIO_RESET_PASSWORD
                ],
                'when' => function(Users $model) {
                    return $model->auto_generate_password == false;
                },
                'whenClient' => "function (attribute, value) {
                    return ($('#users-auto_generate_password').is(':not(:checked)'));
                }",
            ],
            [
                ['confirm'],
                'compare',
                'compareAttribute' => 'password',
                'on' => [
                    self::SCENARIO_CHANGE_PASSWORD,
                    self::SCENARIO_CREATE,
                    self::SCENARIO_RESET_PASSWORD,
                    self::SCENARIO_SIGNUP
                ],
                'message' => Lang::t('Passwords do not match.')
            ],
            [['username'], 'unique', 'message' => 'This username has already been taken.'],
            ['email', 'unique', 'message' => 'This Email address has already been taken.'],
            [['timezone'], 'string', 'max' => 60],
            [['send_email', 'tmp_profile_image', 'org_id', 'client_id'], 'safe'],
            [['phone'], 'string', 'min' => 8, 'max' => 13],
            [['phone'], 'number'],
            [['currentPassword'], 'required', 'on' => self::SCENARIO_CHANGE_PASSWORD],
            [
                ['currentPassword'],
                'validateCurrentPassword',
                'skipOnError' => false,
                'on' => self::SCENARIO_CHANGE_PASSWORD
            ],
            [
                ['status', 'username', 'email', 'phone', 'last_login', self::SEARCH_FIELD],
                'safe',
                'on' => self::SCENARIO_SEARCH
            ],
            static::passwordValidator(),
            ['password', 'passwordHistoryValidator', 'on' => [self::SCENARIO_CHANGE_PASSWORD, self::SCENARIO_RESET_PASSWORD]],
            [['region_id', 'district_id', 'ward_id', 'village_id', 'additional_attributes'], 'safe'],
            [$this->getExcelColumns(), 'safe', 'on' => self::SCENARIO_UPLOAD],
            ['odk_code', 'unique', 'targetAttribute' => ['country_id', 'odk_code'], 'message' => '{attribute} already exists.'],
            [$this->getAdditionalAttributes(), 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Lang::t('ID'),
            'name' => Lang::t('Name'),
            'username' => Lang::t('Username'),
            'email' => Lang::t('Email'),
            'phone' => Lang::t('Mobile'),
            'status' => Lang::t('Status'),
            'timezone' => Lang::t('Timezone'),
            'password' => Lang::t('Password'),
            'confirm' => Lang::t('Confirm Password'),
            'currentPassword' => Lang::t('Current Password'),
            'level_id' => Lang::t('Account Type'),
            'role_id' => Lang::t('Role'),
            'org_id' => Lang::t('Organization'),
            'client_id' => Lang::t('Client'),
            'profile_image' => Lang::t('Profile Image'),
            'tmp_profile_image' => Lang::t('Profile Image'),
            'created_at' => Lang::t('Created At'),
            'created_by' => Lang::t('Created By'),
            'updated_at' => Lang::t('Updated At'),
            'last_login' => Lang::t('Last Login'),
            'send_email' => Lang::t('Email the login details to the user.'),
            'country_id' => Lang::t('Country'),
            'auto_generate_password' => Lang::t('Auto Generate Password'),
            'branch_id' => Lang::t('Branch'),
            'require_password_change' => Lang::t('Force password change on login'),
            'odk_code' => 'ODK Code',
            'region_id' => $this->country !== null ? Html::encode($this->country->unit1_name) : 'Region',
            'district_id' => $this->country !== null ? Html::encode($this->country->unit2_name) : 'District',
            'ward_id' => $this->country !== null ? Html::encode($this->country->unit3_name) : 'Ward',
            'village_id' => $this->country !== null ? Html::encode($this->country->unit4_name) : 'Village',
        ];
    }

    /**
     * @inheritdoc
     */
    public function searchParams()
    {
        return [
            ['email', 'email'],
            ['name', 'name'],
            ['username', 'username'],
            'id',
            'status',
            'level_id',
            'role_id',
            'country_id',
            'is_main_account',
            'branch_id',
            'district_id',
            'ward_id',
            'village_id',
            'org_id',
            'client_id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ignoreAdditionalAttributes = true;
            if ($this->level_id == UserLevels::LEVEL_DEV || $this->level_id == UserLevels::LEVEL_SUPER_ADMIN || $this->level_id == UserLevels::LEVEL_ADMIN) {
                $this->country_id = null;
            }
            $this->setAdditionalAttributesValues();
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->updateProfileImage();
        if ($this->scenario === self::SCENARIO_CREATE) {
            $this->sendLoginDetailsEmail();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAdditionalAttributeValues();
    }


    /**
     * @inheritdoc
     */
    public static function getListData($valueColumn = 'id', $textColumn = 'name', $prompt = false, $condition = '', $params = [], $options = [])
    {
        list($condition, $params) = static::appendOrgSessionIdCondition($condition, $params, false);
        list($condition, $params) = DbUtils::appendCondition('status', self::STATUS_ACTIVE, $condition, $params);
        $options['orderBy'] = ['name' => SORT_ASC];
        return parent::getListData($valueColumn, $textColumn, $prompt, $condition, $params, $options);
    }

    /**
     * @param mixed $condition
     * @param array $params
     * @param string $levelIdAttribute
     * @return array
     * @throws \Exception
     */
    public static function appendLevelCondition($condition = '', $params = [], $levelIdAttribute = 'level_id')
    {
        if (Yii::$app->user->getIsGuest()) {
            return [$condition, $params];
        }
        $levelIds = UserLevels::getColumnData('id', '[[id]]<:id', [':id' => Session::getUserLevelId()]);
        if (empty($levelIds)) {
            return [$condition, $params];
        }
        return DbUtils::appendInCondition($levelIdAttribute, $levelIds, $condition, $params, 'NOT IN');
    }

    /**
     * @param bool $throwException
     * @param bool $allowSameLevel
     * @param bool $allowSameRole
     * @param bool $allowMyAccount
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function checkPermission($throwException = false, $allowSameLevel = true, $allowSameRole = false, $allowMyAccount = true)
    {
        $hasPermission = false;
        if ($this->level_id === null) {
            $hasPermission = true;
        } elseif (Session::getUserLevelId() < $this->level_id) {
            $hasPermission = true;
        } elseif ($allowMyAccount && $this->isMyAccount()) {
            $hasPermission = true;
        } elseif ($allowSameLevel && Session::getUserLevelId() === $this->level_id) {
            $hasPermission = true;
        } elseif ($allowSameRole && Session::getUserRoleId() === $this->role_id && Session::getUserLevelId() === $this->level_id) {
            $hasPermission = true;
        }

        if (!$hasPermission && $throwException) {
            throw new ForbiddenHttpException();
        }
        return $hasPermission;
    }

    /**
     * Get user levels to display in the drop-down list
     * @param mixed $tip
     * @return array
     * @throws \Exception
     */
    public static function levelIdListData($tip = false)
    {
        list($condition, $params) = static::appendLevelCondition(null, [], 'id');
        return UserLevels::getListData('id', 'name', $tip, $condition, $params, ['orderBy' => ['id' => SORT_ASC]]);
    }

    //PROFILE IMAGE HANDLERS

    /**
     * Get the dir of a user
     * @return string
     */
    public function getDir()
    {
        return FileManager::createDir(static::getBaseDir() . DIRECTORY_SEPARATOR . $this->id);
    }

    /**
     * @return mixed
     */
    public static function getBaseDir()
    {
        return FileManager::createDir(FileManager::getUploadsDir() . DIRECTORY_SEPARATOR . self::UPLOADS_DIR);
    }

    /**
     * Update profile image
     */
    protected function updateProfileImage()
    {
        if (empty($this->tmp_profile_image))
            return false;
        //using fineuploader
        $ext = FileManager::getFileExtension($this->tmp_profile_image);
        $image_name = Utils::generateSalt() . '.' . $ext;
        $temp_dir = dirname($this->tmp_profile_image);
        $new_path = $this->getDir() . DIRECTORY_SEPARATOR . $image_name;
        if (copy($this->tmp_profile_image, $new_path)) {
            $this->profile_image = $image_name;
            $this->tmp_profile_image = null;
            $this->save(false);

            if (!empty($temp_dir))
                FileManager::deleteDirOrFile($temp_dir);

            $this->createThumbs($new_path, $image_name);
        }
    }

    /**
     * Create image thumbs
     * @param string $image_path
     * @param string $image_name
     *
     */
    protected function createThumbs($image_path, $image_name)
    {
        $sizes = [
            ['width' => 32, 'height' => 32],
            ['width' => 64, 'height' => 64],
            ['width' => 128, 'height' => 128],
            ['width' => 256, 'height' => 256],
        ];

        $base_dir = $this->getDir();
        foreach ($sizes as $size) {
            $thumb_name = $size['width'] . '_' . $image_name;
            $new_path = $base_dir . DIRECTORY_SEPARATOR . $thumb_name;
            // generate a thumbnail image
            Image::thumbnail($image_path, $size['width'], $size['height'])
                ->save($new_path, ['quality' => 50]);
        }
    }

    public static function getDefaultProfileImagePath()
    {
        return '@authModule/assets/src/img/user.png';
    }

    /**
     * Get profile image
     * @param integer $size
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getProfileImageUrl($size = null)
    {
        $image_path = null;
        $base_dir = $this->getDir() . DIRECTORY_SEPARATOR;
        if (empty($this->profile_image)) {
            $image_path = static::getDefaultProfileImagePath();
        } elseif (!empty($size)) {
            $thumb = $base_dir . $size . '_' . $this->profile_image;
            $image_path = file_exists($thumb) ? $thumb : $base_dir . $this->profile_image;
        } else {
            $image_path = $base_dir . $this->profile_image;
        }

        if (!file_exists($image_path)) {
            $image_path = static::getDefaultProfileImagePath();
        }

        $asset = Yii::$app->getAssetManager()->publish($image_path);

        return $asset[1];
    }

    /**
     * @param integer $level_id
     * @param mixed $scenario
     * @return $this
     * @throws \yii\base\InvalidConfigException
     */
    public static function getInstance($level_id, $scenario = self::SCENARIO_CREATE)
    {
        $role_id = Roles::getScalar('id', ['level_id' => $level_id]);
        $config = [
            'class' => static::class,
            'scenario' => $scenario,
            'level_id' => $level_id,
        ];
        if ($scenario === self::SCENARIO_CREATE) {
            $config = array_merge($config, [
                'status' => Users::STATUS_ACTIVE,
                'send_email' => true,
                'role_id' => $role_id,
            ]);
        }

        return Yii::createObject($config);
    }

    /**
     * @param mixed $condition
     * @param bool $throwException
     * @return Users|null
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public static function loadModel($condition, $throwException = true)
    {
        $model = static::findOne($condition);
        if ($model === null) {
            if ($throwException) {
                throw new NotFoundHttpException('The requested resource was not found.');
            }
        } elseif (Utils::isWebApp() && Session::isCountry() && $model->country_id != Session::getCountryId()) {
            throw new ForbiddenHttpException();
        }
        return $model;
    }

    /**
     * @return string
     */
    public function getDecodedStatus()
    {
        return static::decodeStatus($this->status);
    }

    /**
     * @return int
     */
    public static function getDefinedTableId(): int
    {
        return ExtendableTable::TABLE_USERS;
    }

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        $columns = [
            'name',
            'username',
            'email',
            'phone',
            'region_code',
            'district_code',
            'odk_code',
            'odk_password',
        ];

        return array_merge($columns, $this->getAdditionalAttributes());
    }
}