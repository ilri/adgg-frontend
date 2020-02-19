<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-08-27
 * Time: 10:21 PM
 */

namespace common\excel;


use backend\modules\core\models\ExcelImport;
use common\helpers\DateUtils;
use common\models\ActiveRecord;
use common\models\Model;
use console\jobs\ExcelUploadNotification;
use console\jobs\JobTrait;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class ExcelUploadForm
 * @package common\excel
 */
class ExcelUploadForm extends Model implements JobInterface
{
    use ExcelReaderTrait;
    use JobTrait;

    /**
     * @var string
     */
    public $itemId;

    /**
     * @var string|int
     */
    public $_uploadType;

    /**
     * @var string|ActiveRecord
     */
    public $activeRecordModelClass;

    /**
     * @var ActiveRecord|ImportActiveRecordInterface
     */
    public $targetModel;

    /**
     * UploadProductExcel constructor.
     * @param string $activeRecordModelClass
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(string $activeRecordModelClass, $config = [])
    {
        if (empty($activeRecordModelClass)) {
            throw new InvalidConfigException();
        }
        $config['activeRecordModelClass'] = $activeRecordModelClass;
        parent::__construct($config);

        $this->required_columns = [];
        $className = $this->activeRecordModelClass;
        $this->targetModel = new $className();
        foreach ($this->targetModel->getExcelColumns() as $column) {
            $this->file_columns['[' . $column . ']'] = $this->targetModel->getAttributeLabel($column);
        }
        $this->end_column = static::numberToExcelColumn(count($this->file_columns), true);
    }

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     * @throws \yii\web\NotFoundHttpException
     */
    public function execute($queue)
    {
        $time_start = microtime(true);
        $this->saveExcelData();
        $time_end = microtime(true);
        $executionTime = round($time_end - $time_start, 2);
        $queueModel = ExcelImport::loadModel($this->itemId);
        $queueModel->is_processed = 1;
        $queueModel->processed_at = DateUtils::mysqlTimestamp();
        $queueModel->has_errors = !empty($this->getFailedRows());
        $queueModel->error_message = $this->getFailedRows();
        $queueModel->success_message = $this->getSavedRows();
        $queueModel->processing_duration_seconds = $executionTime;
        $queueModel->save(false);
        ExcelUploadNotification::createManualNotifications(ExcelUploadNotification::NOTIF_EXCEL_UPLOAD_COMPLETION, $this->itemId);
    }

    public function addToExcelQueue()
    {
        try {
            /* @var $queue \yii\queue\cli\Queue */
            $this->saveFile();
            $queue = Yii::$app->queue;
            $this->setUploadType();
            $importQueue = ExcelImport::addToQueue($this->_uploadType, $this->file, $this->country_id ?? null);
            $this->itemId = $importQueue->id;
            $this->created_by = $importQueue->created_by;
            $id = $queue->push($this);

            return $id;
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
    }

    /**
     * @param int $nRow
     */
    protected function updateCurrentProcessedRow($nRow)
    {
        ExcelImport::updateAll(['current_processed_row' => $nRow], ['id' => $this->itemId]);
    }

    /**
     * @param array $data
     * @param ActiveRecord $targetModel
     * @param bool $updateExisting
     * @param string $existingCondition
     * @param array $existingParams
     * @return bool
     */
    public function save(array $data, ActiveRecord $targetModel, $updateExisting = true, $existingCondition = '', $existingParams = [])
    {
        if (empty($data)) {
            return false;
        }

        $nMax = 0;
        foreach ($data as $n => $row) {
            $newModel = null;
            if ($updateExisting) {
                $condition = $existingCondition;
                $params = $existingParams;
                if (is_array($condition)) {
                    $condition = self::prepareInternalExistCondition($condition, $row);
                }
                if (is_array($params) && !empty($params)) {
                    $params = self::prepareInternalExistCondition($params, $row);
                }
                $newModel = $targetModel::find()->andWhere($condition, $params)->one();
            }
            if (null === $newModel) {
                $newModel = clone $targetModel;
            }
            $this->saveExcelRow($newModel, $row, $n);

            $nMax = $n;
        }

        $this->updateCurrentProcessedRow($nMax);
    }

    private static function prepareInternalExistCondition(array $condition, array $rowData)
    {
        foreach ($condition as $k => $v) {
            $pattern = '/{\K[^}]*(?=})/m';
            preg_match($pattern, $v, $match);
            if (!empty($match[0])) {
                $condition[$k] = $rowData[$match[0]] ?? null;
            }
        }

        return $condition;
    }
}