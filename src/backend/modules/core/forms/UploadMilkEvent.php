<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-15
 * Time: 8:56 AM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\ExcelImport;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;
use common\helpers\Lang;

class UploadMilkEvent extends ExcelUploadForm implements ImportInterface
{
    /**
     * @var int
     */
    public $org_id;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge($this->excelValidationRules(), [
            [['org_id'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
            'org_id' => Lang::t('Country'),
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
            $row['org_id'] = $this->org_id;
            $row['event_date'] = static::getDateColumnData($row['event_date'], 'Y-m-d', 'UTC', 'd/m/Y');
            $row['animal_id'] = $this->getAnimalId($row['animalTagId']);
            $insert_data[$k] = $row;
        }

        $this->save($insert_data);
    }


    /**
     * @param array $data
     * @return bool
     */
    public function save($data)
    {
        if (empty($data)) {
            return false;
        }

        $model = new AnimalEvent(['org_id' => $this->org_id, 'event_type' => AnimalEvent::EVENT_TYPE_MILKING]);
        $nMax = 0;
        foreach ($data as $n => $row) {
            $newModel = clone $model;
            $this->saveExcelRaw($newModel, $row, $n);
            $nMax = $n;
        }

        $this->updateCurrentProcessedRow($nMax);
    }

    protected function getAnimalId($tagId)
    {
        $tagId = trim($tagId);
        $animalId = Animal::getScalar('id', ['org_id' => $this->org_id, 'tag_id' => $tagId]);
        if (empty($animalId)) {
            return null;
        }
        return $animalId;
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_ANIMAL_EVENT_MILK;
    }
}