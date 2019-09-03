<?php

namespace backend\modules\core\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use Yii;

/**
 * This is the model class for table "core_excel_import".
 *
 * @property int $id
 * @property string $uuid
 * @property int $org_id
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
 */
class ExcelImport extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    const TYPE_FARM_DATA = 1;
    const TYPE_ANIMAL_DATA = 10;

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
            [['org_id', 'type', 'file_name'], 'required'],
            [['org_id', 'type', 'is_processed', 'has_errors', 'created_by'], 'integer'],
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
            'org_id' => 'Org ID',
            'type' => 'Type',
            'is_processed' => 'Is Processed',
            'processed_at' => 'Processed At',
            'file_name' => 'File Name',
            'has_errors' => 'Has Errors',
            'error_message' => 'Error Message',
            'success_message' => 'Success Message',
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
            ['file_name', 'file_name'],
            ['uuid', 'uuid'],
            'type',
            'is_processed',
            'has_errors',
        ];
    }

    /**
     * @param int $type
     * @param string $fileName
     * @param int $orgId
     * @return ExcelImport
     */
    public static function addToQueue($type, $fileName, $orgId)
    {
        $model = new static([
            'org_id' => $orgId,
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
}
