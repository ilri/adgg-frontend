<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-09-04
 * Time: 9:41 AM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\Animal;
use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\Farm;
use backend\modules\core\models\Country;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;
use common\helpers\Lang;

class UploadAnimals extends ExcelUploadForm implements ImportInterface
{
    /**
     * @var int
     */
    public $country_id;

    /**
     * @var Country
     */
    public $countryModel;

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
            'country_id' => Lang::t('Country'),
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
        $this->countryModel = Country::loadModel($this->country_id);

        foreach ($batch as $k => $excel_row) {
            $row = $this->getExcelRowColumns($excel_row, $columns);
            if (empty($row)) {
                continue;
            }
            $row['country_id'] = $this->country_id;
            $row['farm_id'] = $this->getFarmId($row['farm_id']);
            $row['derivedBirthdate'] = static::getDateColumnData($row['derivedBirthdate'] ?? null);
            $row['birthdate'] = static::getDateColumnData($row['birthdate'] ?? null);
            $row['entry_date'] = static::getDateColumnData($row['entry_date'] ?? null);
            $row['reg_date'] = static::getDateColumnData($row['reg_date'] ?? null);
            $row['animal_sireknown'] = static::encodeBoolean($row['animal_sireknown']);
            $row['animal_damknown'] = static::encodeBoolean($row['animal_damknown']);
            $insert_data[$k] = $row;
        }
        $targetModel = new Animal(['country_id' => $this->country_id]);
        $this->save($insert_data, $targetModel, false);
    }

    /**
     * @param string $farmCode
     * @return bool|string
     * @throws \Exception
     */
    protected function getFarmId($farmCode)
    {
        $condition = '[[odk_code]]=:code';
        $params = [':code' => $farmCode];
        $farmId = Farm::getScalar('id', $condition, $params);

        if (!empty($farmId)) {
            return $farmId;
        }
        return null;
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_ANIMAL_DATA;
    }
}