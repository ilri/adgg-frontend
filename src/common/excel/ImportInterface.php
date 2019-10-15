<?php

namespace common\excel;

use common\models\ActiveRecord;

/**
 * @author Fred <mconyango@gmail.com>
 * Date: 7/23/15
 * Time: 3:16 PM
 */
interface ImportInterface
{
    /**
     * @return boolean
     */
    public function addToExcelQueue();

    /**
     * @return mixed
     */
    public function saveExcelData();

    /**
     * @param $batch
     * @return mixed
     */
    public function processExcelBatchData($batch);

    /**
     * @param ActiveRecord $model
     * @param array $rowData
     * @param integer $rowNumber
     * @return bool
     */
    public function saveExcelRaw(ActiveRecord $model, $rowData, $rowNumber);

    /**
     * @return string|int
     */
    public function setUploadType();
}