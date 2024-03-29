<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-05-12
 * Time: 1:28 PM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\Country;
use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\Farm;
use backend\modules\core\models\FarmMetadata;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;

class UploadFarmMetadata extends ExcelUploadForm implements ImportInterface
{

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

    public function processExcelBatchData($batch)
    {
        $columns = [];
        $insert_data = [];
        $this->countryModel = Country::loadModel($this->country_id);

        /* @var $model FarmMetadata */
        $model = new $this->activeRecordModelClass();
        $model->type = $model::getDefinedMetadataType();
        foreach ($batch as $k => $excel_row) {
            $row = $this->getExcelRowColumns($excel_row, $columns);
            if (empty($row)) {
                continue;
            }
            $row['farm_id'] = $this->getFarmId($row['farmCode']);
            $row['country_id'] = $this->country_id;
            $insert_data[$k] = $row;
        }
        $model = new FarmMetadata(['country_id' => $this->country_id]);
        $this->save($insert_data, $model, false);
    }

    protected function getFarmId($farmCode)
    {
        $farmCode = trim($farmCode);
        $farmId = Farm::getScalar('id', ['odk_code' => $farmCode]);
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