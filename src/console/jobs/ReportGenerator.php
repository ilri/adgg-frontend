<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-08
 * Time: 7:42 AM
 */

namespace console\jobs;


use backend\modules\core\models\Farm;
use backend\modules\core\models\OdkJsonQueue;
use backend\modules\reports\models\AdhocReport;
use common\helpers\DateUtils;
use common\helpers\FileManager;
use common\helpers\Lang;
use Yii;
use yii\base\BaseObject;
use yii\db\Query;
use yii\queue\Queue;

class ReportGenerator extends BaseObject implements JobInterface
{
    /**
     * @var int
     */
    public $queueId;

    /**
     * @var array
     */
    private $_jsonArr;

    /**
     * @var array
     */
    private $_errors;

    /**
     * @var string
     */
    private $_sql;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var string
     */
    public $filepath;

    /**
     * @var yii\db\Query
     */
    private $_query;

    /**
     * @var AdhocReport
     */
    private $_model;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        $this->_model = AdhocReport::find()->andWhere(['id' => $this->queueId])->one();
        if ($this->_model === null) {
            return false;
        }

        try {
            $this->_model->status = AdhocReport::STATUS_PROCESSING;
            $this->_model->save(false);
            $json = json_decode($this->_model->options, true);
            $this->_jsonArr = $json;
            $this->_sql = $this->_model->raw_sql;
            $this->filename = $this->_model->name . '_' . time();
            $this->fetchData();

            //$this->_model->is_processed = 1;
            //$this->_model->processed_at = DateUtils::mysqlTimestamp();
            //$this->_model->save(false);

            //ODKJsonNotification::createManualNotifications(ODKJsonNotification::NOTIF_ODK_JSON, $this->_model->id);
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }
    }

    /**
     * @param mixed $params
     * @return mixed
     */
    public static function push($params)
    {
        try {
            /* @var $queue \yii\queue\cli\Queue */
            $queue = Yii::$app->queue;
            if ($params instanceof self) {
                $obj = $params;
            } else {
                $obj = new self($params);
            }

            $id = $queue->push($obj);

            return $id;
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }
    }

    protected function fetchData(){
        $connection = Yii::$app->getDb();
        try {
            //$connection->setQueryBuilder();
            $command = $connection->createCommand($this->_sql);
            //$result = $command->queryAll();
            $reader = $command->query();
            $count = $reader->rowCount;
            $rows = [];
            $columns = [];
            if ($count > 0) {
                $this->createDataCSV();
                while ($row = $reader->read()) {
                    $rows[] = $row;
                }
                $first = $rows[0];
                $columns = array_keys($first);

                $batch = 500;
                $batches = [];
                if ($count <= $batch) {
                    $batches = [$rows];
                } else {
                    $batches = array_chunk($rows, $batch);
                }
                $this->populateCSV($columns, $rows);
            }
            else {
                $this->_model->status_remarks = Lang::t('No data returned from query');
                $this->_model->save(false);
            }

        }catch (\Exception $e) {
            $this->_model->status_remarks = $e->getMessage();
            $this->_model->save(false);
            Yii::$app->controller->stdout("{$e->getMessage()} \n");
        }

    }
    protected function createDataCSV()
    {
        $fileName = $this->filename. '.csv';
        $this->filepath = $this->getBaseDir() . DIRECTORY_SEPARATOR . $fileName;
    }

    protected function populateCSV($columns, $rows){
        $filepath = $this->filepath;
        try {
            if (count($rows) > 0) {
                $data = [];
                $header = $columns;
                $data[] = $header;
                foreach ($rows as $n => $row) {
                    $data[] = $row;
                }

                $fp = fopen($filepath, 'wb');

                foreach ($data as $fields) {
                    fputcsv($fp, $fields);
                }

                fclose($fp);

                $this->_model->report_file = $filepath;
                $this->_model->status = AdhocReport::STATUS_COMPLETED;
                $this->_model->save(false);
            }
        } catch (\Exception $e) {
            Yii::$app->controller->stdout("{$e->getMessage()} \n");
        }
    }

    public function getBaseDir()
    {
        return FileManager::createDir(FileManager::getUploadsDir() . DIRECTORY_SEPARATOR . 'adhoc-reports');
    }

    public function getFilePath()
    {
        $path = null;
        if (empty($this->filename))
            return null;

        $file = $this->getBaseDir() . DIRECTORY_SEPARATOR . $this->filename;
        if (file_exists($file)) {
            $path = $file;
        }

        return $path;
    }

}