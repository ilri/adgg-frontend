<?php

namespace common\excel;

use backend\modules\core\models\ExcelImport;
use common\helpers\FileManager;
use common\helpers\Lang;
use common\helpers\Str;
use common\models\ActiveRecord;
use DateTime;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Yii;
use yii\base\Exception;

/**
 * Description of MyExcelProcessor
 * @author Fred <mconyango@gmail.com>
 *
 * @method array getExcelColumns()
 */
trait ExcelReaderTrait
{

    /**
     *
     * @var string
     */
    public $file;
    /**
     * @var string
     */
    public $original_file_name;

    /**
     *
     * @var string
     */
    public $sheet;

    /**
     *
     * @var integer
     */
    public $start_row = 1;

    /**
     *
     * @var integer
     */
    public $end_row;

    /**
     *
     * @var string
     */
    public $start_column = 'A';

    /**
     *
     * @var string
     */
    public $end_column = 'J';

    /**
     *
     * @var array
     */
    public $placeholder_columns;

    /**
     *
     * @var array
     */
    public $file_columns = [];

    /**
     *
     * @var array
     */
    private $required_columns = [];

    /**
     *
     * @var array
     */
    public $placeholders;


    /**
     * Delete file after processing
     * @var
     */
    public $delete_file = false;

    public $deleteTmpFile = false;

    /**
     * @var
     */
    public $created_by;

    /**
     *
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    private $_objSpreadsheet;

    /**
     *
     * @var \PhpOffice\PhpSpreadsheet\Reader\IReader
     */
    private $_objReader;

    /**
     * @var array
     */
    protected $_failedRows = [];
    /**
     * @var array
     */
    private $_savedRows = [];

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function setObjReader()
    {
        $input_file_type = PHPExcelHelper::getFileType($this->file);
        $this->_objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($input_file_type);
        $this->_objReader->setReadDataOnly(true);
        //$this->objReader->setDelimiter($this->csv_delimiter);
        $this->_objReader->setLoadSheetsOnly($this->sheet);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function setObjSpreadsheet()
    {
        if (empty($this->_objReader))
            $this->setObjReader();

        $this->_objSpreadsheet = $this->_objReader->load($this->file);
    }


    /**
     * @throws Exception
     */
    public function saveFile()
    {
        $this->original_file_name = basename($this->file);
        $temp_path = $this->file;
        $this->file = time() . '_' . $this->original_file_name;
        $new_path = static::getDir() . DIRECTORY_SEPARATOR . $this->file;
        if (copy($temp_path, $new_path)) {
            if ($this->deleteTmpFile) {
                FileManager::deleteDirOrFile(dirname($temp_path));
            }
        } else {
            throw new Exception('Could not copy the file to the new location.');
        }
    }

    /**
     * @return string
     */
    public static function getBaseDir()
    {
        return FileManager::createDir(FileManager::getUploadsDir() . DIRECTORY_SEPARATOR . 'excel-uploads');
    }

    /**
     * @return string
     */
    public static function getDir()
    {
        return static::getBaseDir();
    }

    /**
     * @return int
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function getTotalRows()
    {
        if (empty($this->_objSpreadsheet))
            $this->setObjSpreadsheet();

        return $this->_objSpreadsheet->getActiveSheet()->getHighestRow();
    }

    /**
     * Set preview data
     * @return array|bool
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function getPreviewData()
    {
        if (!$this->validate(['file', 'start_row', 'end_row', 'start_column', 'end_column']))
            return false;

        if (empty($this->start_column)) {
            $this->start_column = 'A';
        }
        if (empty($this->end_column)) {
            $this->start_column = 'A';
            $this->end_column = 'Z';
        }
        if (empty($this->start_row)) {
            $this->start_row = 1;
        }
        $this->end_row = $this->start_row;
        $this->setObjReader();
        //$this->recipients_count = $this->getRecipientsCount();
        $chunkFilter = $this->getChunkFilter();
        $sheetData = $this->getSheetData($chunkFilter, 1);
        $columnRange = static::getColumnRange($this->start_column, $this->end_column);

        if (isset($sheetData[$this->start_row])) {
            $dataSet = $sheetData[$this->start_row];
            $data = [];
            foreach ($columnRange as $column) {
                $data[$column] = $dataSet[$column] ?? null;
            }
        } else {
            $data = static::getColumnRange($this->start_column, $this->end_column);
        }
        unset($sheetData);
        $this->setPlaceholderColumns($data);
        return $data;
    }

    /**
     * @param array $data
     */
    public function setPlaceholderColumns($data)
    {
        $columns = array_keys($this->file_columns);
        foreach ($data as $k => $v) {
            if (empty($this->placeholder_columns[$k])) {
                $i = static::excelColumnToNumber($k) - 1;
                $this->placeholder_columns[$k] = isset($columns[$i]) ? $columns[$i] : null;
            }
        }
    }

    /**
     *
     */
    public function setPlaceholders()
    {
        $this->placeholders = !empty($this->placeholder_columns) ? array_flip($this->placeholder_columns) : null;
    }

    /**
     * @param array $sheetData
     * @return array
     */
    protected function getInsertBatches($sheetData)
    {
        $insert_batch = 500;
        if (count($sheetData) <= $insert_batch)
            $insert_batches = [$sheetData];
        else
            $insert_batches = array_chunk($sheetData, $insert_batch);

        return $insert_batches;
    }

    /**
     *
     * @return PHPExcelChunkReadFilter
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    protected function getChunkFilter()
    {
        if (empty($this->_objReader))
            $this->setObjReader();

        if (empty($this->end_row)) {
            $this->end_row = $this->getTotalRows();
        }

        $chunkFilter = new PHPExcelChunkReadFilter(static::getColumnRange($this->start_column, $this->end_column));
        $this->_objReader->setReadFilter($chunkFilter);

        return $chunkFilter;
    }

    /**
     * @param PHPExcelChunkReadFilter $chunkFilter
     * @param integer $chunkSize
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    protected function getSheetData($chunkFilter, $chunkSize)
    {
        $endRow = ($this->start_row + $chunkSize) - 1;
        $chunkFilter->setRows($this->start_row, $chunkSize);
        $this->setObjSpreadsheet();
        $activeSheet = $this->_objSpreadsheet->getActiveSheet();
        $sheetData = $this->getSheetDataToArray($activeSheet, $endRow, null, true, true, true);
        foreach ($sheetData as $k => $row) {
            if ($k < $this->start_row) {
                unset($sheetData[$k]);
            }
            if (!empty($this->end_row) && $k > $this->end_row) {
                unset($sheetData[$k]);
            }
        }
        return $sheetData;
    }


    protected function getSheetDataToArray(Worksheet $activeSheet, $endRow, $nullValue = null, $calculateFormulas = true, $formatData = true, $returnCellRef = false)
    {
        // Garbage collect...
        $activeSheet->garbageCollect();

        //    Identify the range that we need to extract from the worksheet
        $minCol = $this->start_column;
        $minRow = !empty($this->start_row) ? $this->start_row : 1;
        $maxCol = $this->end_column;
        $maxRow = !empty($endRow) ? $endRow : $activeSheet->getHighestRow();
        // Return
        return $activeSheet->rangeToArray($minCol . $minRow . ':' . $maxCol . $maxRow, $nullValue, $calculateFormulas, $formatData, $returnCellRef);
    }

    /**
     * @return array
     */
    public function getFileColumns()
    {
        $columns = [];
        foreach ($this->file_columns as $k => $v) {
            $columns[$k] = Lang::t($v);
        }
        return $columns;
    }

    /**
     * preview action
     * @return string|bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function previewAction()
    {
        if ($this->load(Yii::$app->request->post())) {
            if ($data = $this->getPreviewData()) {
                $html = Yii::$app->controller->renderAjax('@common/excel/views/excelColumns', ['model' => $this, 'data' => $data, 'columns' => array_merge(['' => ''], $this->getFileColumns())]);
                $response = ['html' => $html, 'success' => true];
                return json_encode($response);
            } else {
                return json_encode(['success' => false]);
            }
        }
        return false;
    }

    /**
     * @param $sheetData
     * @param int $max_insert_batch
     * @return array
     */
    protected function getExcelInsertBatches($sheetData, $max_insert_batch = 500)
    {
        if (count($sheetData) <= $max_insert_batch) {
            $insert_batches = [$sheetData];
        } else {
            $insert_batches = array_chunk($sheetData, $max_insert_batch, true);
        }

        return $insert_batches;
    }

    /**
     */
    public function processExcelFile()
    {
        try {
            $this->setFile();
            $this->setPlaceholders();
            $chunkFilter = $this->getChunkFilter();
            $batch_size = 2000;
            for ($this->start_row; $this->start_row <= $this->end_row; $this->start_row += $batch_size) {
                $sheetData = $this->getSheetData($chunkFilter, $batch_size);
                foreach ($this->getExcelInsertBatches($sheetData) as $batch) {
                    $this->processExcelBatchData($batch);
                }
                if (count($sheetData) < $batch_size) {
                    break;
                }
            }

            $this->createErrorCSV();

            if ($this->delete_file) {
                @unlink($this->file);
            }
        } catch (\Exception $e) {
            Yii::error($e->getTrace());
            Yii::$app->controller->stdout("{$e->getTraceAsString()}\n");
        }
    }

    public function excelValidationRules()
    {
        return [
            [['file', 'start_row', 'start_column', 'end_column'], 'required'],
            [['start_row', 'end_row'], 'number', 'min' => 1],
            [['sheet', 'placeholder_columns', 'placeholders', 'original_file_name', 'delete_file'], 'safe'],
        ];
    }

    /**
     * Declares excel attribute labels.
     */
    public function excelAttributeLabels()
    {
        return [
            'file' => Lang::t('File'),
            'start_row' => Lang::t('Start Row'),
            'end_row' => Lang::t('End Row'),
            'start_column' => Lang::t('Start Column'),
            'end_column' => Lang::t('End Column'),
        ];
    }

    /**
     * Get columns from excel row
     * @param array $excel_row
     * @param array $columns
     * @return array|bool
     */
    public function getExcelRowColumns($excel_row, $columns = [])
    {
        $i = 1;
        foreach ($this->file_columns as $k => $v) {
            $column_name = isset($this->placeholders[$k]) ? $this->placeholders[$k] : static::numberToExcelColumn($i, true);
            $column = str_replace(['[', ']'], '', $k);
            $data = isset($excel_row[$column_name]) ? trim($excel_row[$column_name]) : null;
            $columns[$column] = strtolower($data) === 'null' ? null : $data;
            $i++;
        }

        if (!empty($this->required_columns)) {
            foreach ($this->required_columns as $c) {
                if (is_null($columns[$c]))
                    return false;
            }
        }

        return $columns;
    }

    public function addToExcelQueue()
    {
        $this->saveFile();
        return true;
        //return ImportExcelQueue::model()->addToQueue($this);
    }

    public function saveExcelData()
    {
        $this->processExcelFile();
    }

    public function setFile()
    {
        $this->file = $this->getDir() . DIRECTORY_SEPARATOR . $this->file;
    }

    /**
     * @param string $start_column
     * @param string $end_column
     * @return array
     */
    public static function getColumnRange($start_column, $end_column)
    {
        $columns = [];
        foreach (PHPExcelHelper::excelColumnRange($start_column, $end_column) as $value) {
            $columns[] = $value;
        }

        return $columns;
    }

    /**
     * @return array
     */
    public function getSavedRows()
    {
        return $this->_savedRows;
    }

    /**
     * @return array
     */
    public function getFailedRows()
    {
        return $this->_failedRows;
    }

    /**
     * @param ActiveRecord $model
     * @param array $rowData
     * @param integer $rowNumber
     * @return bool
     */
    public function saveExcelRow(ActiveRecord $model, $rowData, $rowNumber)
    {
        foreach ($rowData as $k => $v) {
            if ($model->hasAttribute($k) || property_exists($model, $k)) {
                $model->{$k} = $v;
            }
        }
        $model->enableAuditTrail = false;
        $model->setScenario($model::SCENARIO_UPLOAD);
        try {
            if ($model->save()) {
                $this->_savedRows[$rowNumber] = $rowNumber;
                Yii::$app->controller->stdout("Row {$rowNumber} saved successfully\n");
                return true;
            } else {
                $errors = $model->getFirstErrors();
                $error = implode(', ', $errors);
                $this->_failedRows[$rowNumber] = ['error' => Lang::t('{error}', ['error' => $error]), 'rowData' => $rowData, 'rowNumber' => $rowNumber];
                Yii::$app->controller->stdout("Row {$rowNumber} failed. Error: {$error} \n");
                return false;
            }
        } catch (\Exception $e) {
            \Yii::$app->controller->stdout("Exception: {$e->getMessage()} \n");
            Yii::error($e->getTrace());
            $this->_failedRows[$rowNumber] = ['error' => $e->getMessage(), 'rowData' => $rowData, 'rowNumber' => $rowNumber];
        }
    }

    /**
     * @param string $booleanString
     * @param null|int $default
     * @return int
     */
    public static function encodeBoolean($booleanString, $default = null)
    {
        if (Str::isEmpty($booleanString)) {
            return $default;
        }
        $booleanString = strtolower($booleanString);
        if ($booleanString === 'yes' || $booleanString === 'y') {
            return 1;
        } elseif ($booleanString === 'no' || $booleanString === 'n') {
            return 0;
        }
        return $default !== null ? $default : $booleanString;
    }


    /**
     * @param int $number
     * @param bool $toUpper
     * @return string
     */
    public static function numberToExcelColumn(int $number, $toUpper = true)
    {
        $number = intval($number);
        if ($number <= 0) return '';

        $letter = '';

        while ($number != 0) {
            $p = ($number - 1) % 26;
            $number = intval(($number - $p) / 26);
            $letter = chr(65 + $p) . $letter;
        }

        if ($toUpper) {
            return strtoupper($letter);
        }
        return $letter;
    }

    /**
     * Takes a letter and converts it to number
     * @access    public
     * @param $string
     * @return int number from letter input
     */
    public static function excelColumnToNumber(string $string)
    {
        $num = 0;
        $string = strtolower($string);
        $string = str_split($string);
        $exp = count($string) - 1;
        foreach ($string as $char) {
            $digit = ord($char) - 96;
            $num += $digit * pow(26, $exp);

            $exp--;
        }

        return $num;
    }

    /**
     * @param string $dateString
     * @param string $format
     * @param string $timezone
     * @param null|string $createDateFromFormat
     * @return string|null
     * @throws \Exception
     */
    public static function getDateColumnData($dateString, $format = 'Y-m-d', $timezone = 'UTC', $createDateFromFormat = null)
    {
        if (empty($dateString)) {
            return null;
        }
        if (is_numeric($dateString)) {
            $dateString = Date::excelToDateTimeObject($dateString)->format($format);
        } else {
            if (!empty($createDateFromFormat)) {
                $date = DateTime::createFromFormat($createDateFromFormat, $dateString, $timezone);
                $dateString = $date->format($format);
            }
        }

        return $dateString;
    }

    protected function createErrorCSV()
    {
        try {
            $model = ExcelImport::loadModel($this->itemId);
            $baseDir = $model->getErrorCsvBaseDir();
            $failedRows = $this->getFailedRows();
            if (count($failedRows) > 0) {
                $failedData = [];
                $header = [];
                foreach ($this->targetModel->getExcelColumns() as $column) {
                    $header[$column] = $this->targetModel->getAttributeLabel($column);
                }
                $header['_error_'] = 'Error Message';
                $failedData[] = $header;
                foreach ($failedRows as $n => $data) {
                    $rowData = $data['rowData'];
                    foreach ($rowData as $k => $v) {
                        if (!key_exists($k, $header)) {
                            unset($rowData[$k]);
                        }
                    }
                    $rowData['_error_' . $n] = $data['error'];
                    $failedData[] = $rowData;
                }

                $fileName = pathinfo($model->getFilePath(), PATHINFO_FILENAME);
                $fileName = $fileName . '_error.csv';
                $filePath = $baseDir . DIRECTORY_SEPARATOR . $fileName;
                $fp = fopen($filePath, 'wb');

                foreach ($failedData as $fields) {
                    fputcsv($fp, $fields);
                }

                fclose($fp);

                $model->error_csv = $fileName;
                $model->save(false);
            }
        } catch (\Exception $e) {
            Yii::$app->controller->stdout("{$e->getMessage()} \n");
        }
    }
}