<?php

namespace backend\modules\reports\models;

use backend\modules\auth\models\Users;
use common\helpers\FileManager;
use common\helpers\Lang;
use common\helpers\Utils;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "reports".
 *
 * @property int $id
 * @property string $name
 * @property string $raw_sql
 * @property string $report_file
 * @property string $options
 * @property int $status
 * @property string $status_remarks
 * @property string $created_at
 * @property int $created_by
 *
 * @property Users $extractedBy
 */
class AdhocReport extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    //status constants
    const STATUS_QUEUED = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_ERROR = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%report_adhoc}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'raw_sql', 'status'], 'required'],
            [['status'], 'integer'],
            [['name', 'report_file'], 'string', 'max' => 255],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Lang::t('#'),
            'name' => Lang::t('Name'),
            'raw_sql' => Lang::t('Raw SQL'),
            'status' => Lang::t('Status'),
            'report_file' => Lang::t('Report File'),
            'status_remarks' => Lang::t('Status Remarks'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            'status',
            'created_by',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtractedBy()
    {
        return $this->hasOne(Users::class, ['id' => 'created_by']);
    }

    /**
     * @param $status
     * @return string
     */
    public static function decodeStatus($status)
    {
        $decoded = $status;
        switch ($status) {
            case self::STATUS_QUEUED:
                $decoded = Lang::t('Queued');
                break;
            case self::STATUS_PROCESSING:
                $decoded = Lang::t('Processing');
                break;
            case self::STATUS_COMPLETED:
                $decoded = Lang::t('Completed');
                break;
            case self::STATUS_ERROR:
                $decoded = Lang::t('Error');
                break;
        }

        return $decoded;
    }

    /**
     * Status options that can be used in dropdown list
     * @param mixed $tip
     * @return array
     */
    public static function statusOptions($tip = false)
    {
        return Utils::appendDropDownListPrompt([
            self::STATUS_QUEUED => static::decodeStatus(self::STATUS_QUEUED),
            self::STATUS_PROCESSING => static::decodeStatus(self::STATUS_PROCESSING),
            self::STATUS_COMPLETED => static::decodeStatus(self::STATUS_COMPLETED),
            self::STATUS_ERROR => static::decodeStatus(self::STATUS_ERROR),
        ], $tip);
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return FileManager::createDir(FileManager::getUploadsDir() . DIRECTORY_SEPARATOR . 'adhoc-reports');
    }

    /**
     * @return null|string
     */
    public function getFilePath()
    {
        $path = null;
        if (empty($this->report_file))
            return null;

        $file = $this->getBaseDir() . DIRECTORY_SEPARATOR . $this->report_file;
        if (file_exists($file)) {
            $path = $file;
        }

        return $path;
    }

}
