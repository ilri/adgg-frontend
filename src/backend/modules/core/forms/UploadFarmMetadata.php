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
use yii\base\InvalidConfigException;

class UploadFarmMetadata extends ExcelUploadForm implements ImportInterface
{

    /**
     * @var int
     */
    public $countryId;

    /**
     * @var string
     */
    public $metadataTypeModelClass;

    public function init()
    {
        parent::init();

        if (empty($this->countryId)) {
            throw new InvalidConfigException('countryId cannot be blank. Pass countryId when initializing the object.');
        }
        if (empty($this->metadataTypeModelClass)) {
            throw new InvalidConfigException('metadataTypeModelClass cannot be blank. Pass metadataTypeModelClass when initializing the object.');
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge($this->excelValidationRules(), [
            [['countryId', 'metadataTypeModelClass'], 'required'],
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

        /* @var $model FarmMetadata */
        $model = new $this->metadataTypeModelClass();
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
        $farmId = Farm::getScalar('id', ['country_id' => $this->countryId, 'code' => $farmCode]);
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