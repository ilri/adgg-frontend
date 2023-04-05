<?php
/**
 * Created by PhpStorm.
 * @author: Mirieri Mogaka <davemirieri@gmail.com>
 * Date: 2019-10-15
 * Time: 8:56 AM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\StrawEvent;
use common\excel\ImportInterface;

class UploadStrawEvent extends UploadAnimalEvent implements ImportInterface
{
    public function init()
    {
        parent::init();
        $this->event_type = AnimalEvent::EVENT_TYPE_STRAW;
        $this->sampleExcelFileName = 'straw-event.xlsx';
    }


    /**
     * @param $batch
     * @return mixed
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
            $row['field_agent_id'] = $this->getFieldAgentId($row['field_agent_id']);
//            $row['breeding_pdservicedate'] = static::getDateColumnData($row['breeding_pdservicedate']);
            $insert_data[$k] = $row;
        }

        $model = new StrawEvent(['country_id' => $this->country_id, 'event_type' => $this->event_type]);
        $this->save($insert_data, $model, false);
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_ANIMAL_EVENT_STRAW;
    }
}