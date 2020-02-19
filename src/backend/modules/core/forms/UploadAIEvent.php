<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-01-13
 * Time: 11:44 AM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\AIEvent;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\ExcelImport;
use common\excel\ImportInterface;

class UploadAIEvent extends UploadAnimalEvent implements ImportInterface
{
    public function init()
    {
        parent::init();
        $this->event_type = AnimalEvent::EVENT_TYPE_AI;
        $this->sampleExcelFileName = 'AI-event.xlsx';
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
            $insert_data[$k] = $row;
        }

        $model = new AIEvent(['country_id' => $this->country_id, 'event_type' => $this->event_type]);
        $this->save($insert_data, $model, false);
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_ANIMAL_EVENT_AI;
    }
}