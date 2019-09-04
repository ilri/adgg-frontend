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
use yii\base\InvalidConfigException;
use yii\queue\Queue;

class ExcelUploadForm extends Model
{
    use ExcelReaderTrait;

    /**
     * @var string|ActiveRecord
     */
    public $activeRecordModelClass;

    /**
     * @var ActiveRecord|ImportActiveRecordInterface
     */
    private $_model;

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
        $this->_model = new $className();
        foreach ($this->_model->getExcelColumns() as $column) {
            $this->file_columns['[' . $column . ']'] = $this->_model->getAttributeLabel($column);
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
    }
}