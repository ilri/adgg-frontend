<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-10-05
 * Time: 8:01 PM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\AnimalUpdate;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;

class UpdateAnimals extends ExcelUploadForm implements ImportInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge($this->excelValidationRules(), [
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
        ]);
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
            $row['birthdate'] = static::getDateColumnData($row['birthdate'] ?? null);
            $insert_data[$k] = $row;
        }
        $targetModel = new AnimalUpdate();
        $this->save($insert_data, $targetModel, true, ['tag_id' => '{tag_id}']);
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_UPDATE_ANIMAL_DATA;
    }
}