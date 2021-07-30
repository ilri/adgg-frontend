<?php


namespace backend\modules\core\forms;


use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\HairsamplingEvent;
use common\excel\ImportInterface;

class UploadHairsamplingEvent extends UploadAnimalEvent implements ImportInterface
{
    public function init()
    {
        parent::init();
        $this->event_type = AnimalEvent::EVENT_TYPE_HAIR_SAMPLING;
        $this->sampleExcelFileName = 'hairsampling-event.xlsx';
    }


    /**
     * @param $batch
     * @return mixed|void
     * @throws \Exception
     */
    public function processExcelBatchData($batch)
    {
        $columns = [];
        $insert_data = [];

        foreach ($batch as $k => $excel_row) {
            $row = $this->getExcelRowColumns($excel_row, $columns);
            if (empty($row)) {
                continue;
            }
            $row = $this->setDefaultAttributes($row);
            $insert_data[$k] = $row;
        }

        $model = new HairsamplingEvent(['country_id' => $this->country_id, 'event_type' => $this->event_type]);
        $this->save($insert_data, $model, false);
    }


    /**
     * @return int|string|void
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_ANIMAL_EVENT_HAIRSAMPLING;
    }
}