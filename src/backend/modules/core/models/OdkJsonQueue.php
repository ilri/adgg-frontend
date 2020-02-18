<?php

namespace backend\modules\core\models;

use common\helpers\FileManager;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "core_odk_json_queue".
 *
 * @property int $id
 * @property string $uuid
 * @property string $file
 * @property int $is_processed
 * @property string $processed_at
 * @property int $org_id
 * @property int $has_errors
 * @property string $error_message
 * @property string $file_contents
 * @property int $is_locked
 * @property string $created_at
 * @property int $created_by
 */
class OdkJsonQueue extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait, OrganizationDataTrait;

    const SCENARIO_UPLOAD = 'upload';
    const SCENARIO_API_PUSH = 'api_push';

    /**
     * @var UploadedFile
     */
    public $jsonFile;

    /**
     * @var string
     */
    public $tmp_file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_odk_json_queue}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid'], 'required'],
            ['tmp_file', 'required', 'on' => [self::SCENARIO_UPLOAD, self::SCENARIO_API_PUSH]],
            [['is_processed', 'org_id', 'has_errors', 'is_locked'], 'integer'],
            [['error_message', 'file_contents'], 'string'],
            [['uuid', 'file'], 'string', 'max' => 255],
            //[['uuid'], 'unique', 'message' => Lang::t('{attribute} already exists.')],
            [['jsonFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['json', 'xml'], 'checkExtensionByMimeType' => false, 'on' => self::SCENARIO_API_PUSH],
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
            'uuid' => 'JSON FIle UUID',
            'file' => 'JSON File',
            'tmp_file' => 'JSON File',
            'is_processed' => 'Is Processed',
            'processed_at' => 'Processed At',
            'org_id' => 'Country ID',
            'has_errors' => 'Has Errors',
            'error_message' => 'Error Message',
            'file_contents' => 'Json',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['uuid', 'uuid'],
            'is_processed',
            'org_id',
            'has_errors',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->setFile();
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            //ProcessODKJson::push(['queueId' => $this->id]);
        }
    }


    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (!empty($this->tmp_file)) {
                $this->setJsonAttributes($this->tmp_file);
            }

            return true;
        }
        return false;
    }

    /**
     * @param string $file
     * @return void
     */
    public function setJsonAttributes($file)
    {
        $jsonStr = file_get_contents($file);
        $json = json_decode($jsonStr, true);
        $this->uuid = $json['_uuid'];
        $this->file_contents = $json;
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
        return FileManager::createDir(FileManager::getUploadsDir() . DIRECTORY_SEPARATOR . 'odk-json');
    }

    protected function setFile()
    {
        if (empty($this->tmp_file))
            return false;

        $ext = pathinfo($this->tmp_file, PATHINFO_EXTENSION);
        $file_name = $this->uuid . '.' . $ext;
        $temp_dir = $this->scenario === self::SCENARIO_API_PUSH ? $this->tmp_file : dirname($this->tmp_file);
        $new_path = $this->getDir() . DIRECTORY_SEPARATOR . $file_name;
        if (copy($this->tmp_file, $new_path)) {
            $this->file = $file_name;
            $this->tmp_file = null;

            if (!empty($temp_dir)) {
                FileManager::deleteDirOrFile($temp_dir);
            }
        }
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getFileUrl()
    {
        $file_path = $this->getFilePath();
        if (empty($file_path)) {
            return null;
        }
        $asset = Yii::$app->getAssetManager()->publish($file_path);

        return $asset[1];
    }

    /**
     * @return null|string
     */
    public function getFilePath()
    {
        $path = null;
        if (empty($this->file))
            return null;

        $file = $this->getDir() . DIRECTORY_SEPARATOR . $this->file;
        if (file_exists($file)) {
            $path = $file;
        }

        return $path;
    }
}
