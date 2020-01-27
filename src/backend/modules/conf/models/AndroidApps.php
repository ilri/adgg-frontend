<?php

namespace backend\modules\conf\models;

use common\helpers\FileManager;
use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use Yii;

/**
 * This is the model class for table "android_apps".
 *
 * @property integer $id
 * @property string $version_code
 * @property string $version_name
 * @property string $app_url
 * @property string $apk_file
 * @property integer $is_active
 * @property string $created_at
 * @property integer $created_by
 */
class AndroidApps extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    const APK_DIR = 'apk';

    /**
     * @var string
     */
    public $temp_apk_file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%android_apps}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['version_code', 'version_name'], 'required'],
            [['is_active'], 'integer'],
            [['version_code'], 'string', 'max' => 20],
            [['version_name'], 'string', 'max' => 30],
            [['app_url', 'temp_apk_file'], 'string', 'max' => 255],
            [['version_code', 'version_name'], 'unique'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Lang::t('ID'),
            'version_code' => Lang::t('Version Code'),
            'version_name' => Lang::t('Version Name'),
            'app_url' => Lang::t('App URL'),
            'is_active' => Lang::t('Active'),
            'apk_file' => Lang::t('APK File'),
            'created_at' => Lang::t('Created At'),
            'created_by' => Lang::t('Created By'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->updateAPKFile();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Update profile image
     */
    protected function updateAPKFile()
    {
        if (empty($this->temp_apk_file))
            return false;
        //using fine-uploader
        $ext = FileManager::getFileExtension($this->temp_apk_file);
        $env = YII_ENV === 'dev' ? 'uat-' : '';
        $file_name = 'adgg-' . $env . $this->version_name . '.' . $ext;
        $temp_dir = dirname($this->temp_apk_file);
        $new_path = static::getDir() . DIRECTORY_SEPARATOR . $file_name;
        if (copy($this->temp_apk_file, $new_path)) {
            $this->apk_file = $file_name;
            $this->app_url = static::getAPKUrl($file_name);
            $this->temp_apk_file = null;
            $this->save(false);

            if (!empty($temp_dir))
                FileManager::deleteDirOrFile($temp_dir);
        }
    }

    /**
     * Get the APK directory path
     * @return string
     */
    public static function getDir()
    {
        return FileManager::createDir(FileManager::getUploadsDir() . DIRECTORY_SEPARATOR . self::APK_DIR);
    }

    /**
     * @param $apk_file
     * @return string
     */
    public static function getAPKUrl($apk_file)
    {
        return static::getAPKBaseUrl() . '/' . $apk_file;
    }

    /**
     * @return string
     */
    public static function getAPKBaseUrl()
    {
        return Yii::$app->getUrlManager()->createAbsoluteUrl(['/uploads/' . self::APK_DIR]);
    }

    /**
     * @inheritdoc
     */
    public function searchParams()
    {
        return [
            ['version_code', 'version_code'],
            ['version_name', 'version_name'],
            'is_active',
        ];
    }

    /**
     * @return null|string
     */
    public function getAPKPath()
    {
        $path = null;
        if (!empty($this->apk_file)) {
            $file = static::getDir() . DIRECTORY_SEPARATOR . $this->apk_file;
            if (file_exists($file)) {
                $path = $file;
            }
        }

        return $path;
    }


}
