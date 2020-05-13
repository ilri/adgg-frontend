<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-05-12
 * Time: 1:28 PM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\Farm;
use backend\modules\core\models\FarmMetadata;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;

class UploadFarmMetadata extends ExcelUploadForm implements ImportInterface
{

    /**
     * @var int
     */
    public $country_id;

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
            [['country_id'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
            'country_id' => 'Country',
        ]);
    }

    public function processExcelBatchData($batch)
    {
        $columns = [];
        $insert_data = [];

        /* @var $model FarmMetadata */
        $model = new $this->activeRecordModelClass();
        $model->type = $model::getDefineMetadataType();
        foreach ($batch as $k => $excel_row) {
            $row = $this->getExcelRowColumns($excel_row, $columns);
            if (empty($row)) {
                continue;
            }
            $row['farm_id'] = $this->getFarmId($row['farmCode']);
            $insert_data[$k] = $row;
        }
        $this->save($insert_data, $model, false);
    }

    protected function getFarmId($farmCode)
    {
        $farmCode = trim($farmCode);
        $farmId = Farm::getScalar('id', ['country_id' => $this->country_id, 'odk_code' => $farmCode]);
        if (empty($farmId)) {
            return null;
        }
        return $farmId;
    }

    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_FARM_METADATA;
    }
}