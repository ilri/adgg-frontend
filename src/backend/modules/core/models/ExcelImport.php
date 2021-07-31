<?php

namespace backend\modules\core\models;

use common\helpers\FileManager;
use common\helpers\Lang;
use common\helpers\Utils;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use yii\base\InvalidArgumentException;

/**
 * This is the model class for table "core_excel_import".
 *
 * @property int $id
 * @property string $uuid
 * @property int $country_id
 * @property int $type
 * @property int $is_processed
 * @property string $processed_at
 * @property string $file_name
 * @property int $has_errors
 * @property array $error_message
 * @property array $success_message
 * @property float $processing_duration_seconds
 * @property string $created_at
 * @property int $created_by
 * @property int $current_processed_row
 * @property string $error_csv
 */
class ExcelImport extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    const TYPE_FARM_DATA = 1;
    const TYPE_FARM_METADATA = 2;
    const TYPE_ANIMAL_DATA = 10;
    const TYPE_UPDATE_ANIMAL_DATA = 11;
    const TYPE_HERD_DATA = 20;
    const TYPE_ANIMAL_EVENT_CALVING = 30;
    const TYPE_ANIMAL_EVENT_MILK = 31;
    const TYPE_ANIMAL_EVENT_AI = 32;
    const TYPE_ANIMAL_EVENT_SYNC = 33;
    const TYPE_ANIMAL_EVENT_PD = 34;
    const TYPE_ANIMAL_EVENT_WEIGHT = 35;
    const TYPE_ANIMAL_EVENT_FEEDING =37;
    const TYPE_ANIMAL_EVENT_HEALTH = 36;
    const TYPE_ANIMAL_EVENT_EXITS = 38;
    const TYPE_ORGANIZATION_REF_UNITS = 40;
    const TYPE_USERS = 60;
    const TYPE_ANIMAL_EVENT_VACCINATION =41;
    const TYPE_ANIMAL_EVENT_INJURY = 42;
    const TYPE_ANIMAL_EVENT_HOOF_HEALTH = 43;
    const TYPE_ANIMAL_EVENT_HOOF_TREATMENT = 44;
    const TYPE_ANIMAL_EVENT_SAMPLING = 45;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_excel_import}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'type', 'file_name'], 'required'],
            [['country_id', 'type', 'is_processed', 'has_errors', 'created_by'], 'integer'],
            [['processed_at', 'error_message', 'success_message', 'created_at'], 'safe'],
            [['uuid', 'file_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'country_id' => 'Country ID',
            'type' => 'Type',
            'is_processed' => 'Is Processed',
            'processed_at' => 'Processed At',
            'file_name' => 'File Name',
            'has_errors' => 'Has Errors',
            'error_message' => 'Error Message',
            'success_message' => 'Success Message',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'current_processed_row' => 'Processed Rows',
            'processing_duration_seconds' => 'Processing Duration (Sec)',

        ];
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['file_name', 'file_name'],
            ['uuid', 'uuid'],
            'type',
            'country_id',
            'is_processed',
            'has_errors',
        ];
    }

    /**
     * @param int $type
     * @param string $fileName
     * @param int $countryId
     * @return ExcelImport
     */
    public static function addToQueue($type, $fileName, $countryId)
    {
        $model = new static([
            'country_id' => $countryId,
            'type' => $type,
            'file_name' => $fileName,
        ]);

        $model->save(false);

        return $model;
    }

    /**
     * @return string
     */
    public function getSavedRowsMessage()
    {
        return Lang::t('{n} rows successfully uploaded.', ['n' => count($this->success_message)]);
    }

    /**
     * @return string
     */
    public function getFailedRowsMessage()
    {
        $warningMsg = '<p>' . Lang::t('{n} rows could could not be saved.', ['n' => count($this->error_message)]) . '</p>';
        $warningMsg .= '<ul style="max-height: 200px;overflow: auto">';
        foreach ($this->error_message as $n => $message) {
            $warningMsg .= '<li>' . $message . '</li>';
        }
        $warningMsg .= '</ul>';

        return $warningMsg;
    }

    /**
     * @param int $intVal
     * @return string
     */
    public static function decodeType($intVal): string
    {
        switch ($intVal) {
            case self::TYPE_FARM_DATA:
                return 'Farm Data';
            case self::TYPE_FARM_METADATA:
                return 'Farm Metadata';
            case self::TYPE_ANIMAL_DATA:
                return 'Animal Data';
            case self::TYPE_UPDATE_ANIMAL_DATA:
                return 'Update Animal Data';
            case self::TYPE_HERD_DATA:
                return 'Herd Data';
            case self::TYPE_ANIMAL_EVENT_CALVING:
                return 'Calving Data';
            case self::TYPE_ANIMAL_EVENT_MILK:
                return 'Milking Data';
            case self::TYPE_ANIMAL_EVENT_AI:
                return 'Artificial Insemination';
            case self::TYPE_ANIMAL_EVENT_SYNC:
                return 'Synchronization';
            case self::TYPE_ANIMAL_EVENT_PD:
                return 'Pregnancy Diagnosis';
            case self::TYPE_ANIMAL_EVENT_WEIGHT:
                return 'Weight';
            case self::TYPE_ANIMAL_EVENT_FEEDING:
                return 'feeding';
            case self::TYPE_ANIMAL_EVENT_HEALTH:
                return 'Health';
            case self::TYPE_ANIMAL_EVENT_EXITS:
                return 'Exits';
            case self::TYPE_ANIMAL_EVENT_HAIRSAMPLING:
                return 'Hairsampling';
            case self::TYPE_ORGANIZATION_REF_UNITS:
                return 'Country Administrative Units';
            case self::TYPE_USERS:
                return 'Users';
            default:
                throw new InvalidArgumentException();

        }
    }

    /**
     * @param mixed $prompt
     * @return array
     */
    public static function typeOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::TYPE_FARM_DATA => static::decodeType(self::TYPE_FARM_DATA),
            self::TYPE_FARM_METADATA => static::decodeType(self::TYPE_FARM_METADATA),
            self::TYPE_ANIMAL_DATA => static::decodeType(self::TYPE_ANIMAL_DATA),
            self::TYPE_HERD_DATA => static::decodeType(self::TYPE_HERD_DATA),
            self::TYPE_ANIMAL_EVENT_CALVING => static::decodeType(self::TYPE_ANIMAL_EVENT_CALVING),
            self::TYPE_ANIMAL_EVENT_MILK => static::decodeType(self::TYPE_ANIMAL_EVENT_MILK),
            self::TYPE_ANIMAL_EVENT_AI => static::decodeType(self::TYPE_ANIMAL_EVENT_AI),
            self::TYPE_ANIMAL_EVENT_SYNC => static::decodeType(self::TYPE_ANIMAL_EVENT_SYNC),
            self::TYPE_ANIMAL_EVENT_PD => static::decodeType(self::TYPE_ANIMAL_EVENT_PD),
            self::TYPE_ANIMAL_EVENT_WEIGHT => static::decodeType(self::TYPE_ANIMAL_EVENT_WEIGHT),
            self::TYPE_ANIMAL_EVENT_FEEDING => static::decodeType(self::TYPE_ANIMAL_EVENT_FEEDING),
            self::TYPE_ANIMAL_EVENT_HEALTH => static::decodeType(self::TYPE_ANIMAL_EVENT_HEALTH),
            self::TYPE_ANIMAL_EVENT_EXITS => static::decodeType(self::TYPE_ANIMAL_EVENT_EXITS),
            self::TYPE_ANIMAL_EVENT_HAIRSAMPLING => static::decodeType(self::TYPE_ANIMAL_EVENT_HAIRSAMPLING),
            self::TYPE_ORGANIZATION_REF_UNITS => static::decodeType(self::TYPE_ORGANIZATION_REF_UNITS),
            self::TYPE_USERS => static::decodeType(self::TYPE_USERS),
        ], $prompt);
    }

    /**
     * @return string
     */
    public function getDecodedType()
    {
        return static::decodeType($this->type);
    }

    public function getErrorCsvBaseDir()
    {
        return FileManager::createDir(FileManager::getUploadsDir() . DIRECTORY_SEPARATOR . 'excel-upload-errors');
    }

    /**
     * @return null|string
     */
    public function getCSVErrorFilePath()
    {
        $path = null;
        if (empty($this->error_csv))
            return null;

        $file = $this->getErrorCsvBaseDir() . DIRECTORY_SEPARATOR . $this->error_csv;
        if (file_exists($file)) {
            $path = $file;
        }

        return $path;
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return FileManager::createDir(FileManager::getUploadsDir() . DIRECTORY_SEPARATOR . 'excel-uploads');
    }

    /**
     * @return null|string
     */
    public function getFilePath()
    {
        $path = null;
        if (empty($this->file_name))
            return null;

        $file = $this->getBaseDir() . DIRECTORY_SEPARATOR . $this->file_name;
        if (file_exists($file)) {
            $path = $file;
        }

        return $path;
    }
}
