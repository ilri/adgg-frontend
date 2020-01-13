<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-01-14
 * Time: 12:56 AM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\PDEvent;
use common\excel\ImportInterface;

class UploadPDEvent extends UploadAnimalEvent implements ImportInterface
{
    public function init()
    {
        parent::init();
        $this->event_type = AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS;
        $this->sampleExcelFileName = 'pd-event.xlsx';
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

        $model = new PDEvent(['org_id' => $this->org_id, 'event_type' => $this->event_type]);
        $this->save($insert_data, $model, false);
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_ANIMAL_EVENT_PD;
    }
}