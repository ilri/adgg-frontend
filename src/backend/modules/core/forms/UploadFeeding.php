<?php


namespace backend\modules\core\forms;


use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\FeedingEvent;
use common\excel\ImportInterface;

class UploadFeeding extends UploadAnimalEvent implements ImportInterface
{
    public function init()
    {
        parent::init();
        $this->event_type = AnimalEvent::EVENT_TYPE_FEEDING;
        $this->sampleExcelFileName = 'feeding-event.xlsx';
    }

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

        $model = new FeedingEvent(['country_id' => $this->country_id, 'event_type' => $this->event_type]);
        $this->save($insert_data, $model, false);
    }

    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_ANIMAL_EVENT_FEEDING;
    }
}