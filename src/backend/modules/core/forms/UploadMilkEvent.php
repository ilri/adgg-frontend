<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-15
 * Time: 8:56 AM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\ExcelImport;
use common\excel\ImportInterface;

class UploadMilkEvent extends UploadAnimalEvent implements ImportInterface
{
    public function init()
    {
        parent::init();
        $this->event_type = AnimalEvent::EVENT_TYPE_MILKING;
        $this->sampleExcelFileName = 'milking-event.xlsx';
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
            $row['org_id'] = $this->org_id;
            $row['event_date'] = static::getDateColumnData($row['event_date'], 'Y-m-d', 'UTC', 'd/m/Y');
            $row['animal_id'] = $this->getAnimalId($row['animalTagId']);
            $insert_data[$k] = $row;
        }
        $model = new AnimalEvent(['org_id' => $this->org_id, 'event_type' => $this->event_type]);
        $this->save($insert_data, $model);
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_ANIMAL_EVENT_MILK;
    }
}