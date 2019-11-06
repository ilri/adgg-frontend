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
use backend\modules\core\models\Organization;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;
use common\helpers\Lang;
use console\jobs\JobInterface;

class UploadAnimals extends ExcelUploadForm implements ImportInterface, JobInterface
{
    /**
     * @var int
     */
    public $org_id;

    /**
     * @var Organization
     */
    public $orgModel;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge($this->excelValidationRules(), [
            [['org_id', 'type'], 'required'],
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
        $this->orgModel = Organization::loadModel($this->org_id);

        foreach ($batch as $k => $excel_row) {
            $row = $this->getExcelRowColumns($excel_row, $columns);
            if (empty($row)) {
                continue;
            }
            $row['org_id'] = $this->org_id;
            $row['farm_id'] = $this->getFarmId($row['odkFarmCode']);

            $row['derivedBirthdate'] = static::getDateColumnData($row['derivedBirthdate'] ?? null);
            $row['birthdate'] = static::getDateColumnData($row['birthdate'] ?? null);
            $row['entry_date'] = static::getDateColumnData($row['entry_date'] ?? null);
            if (empty($row['birthdate']) && !empty($row['derivedBirthdate'])) {
                $row['birthdate'] = $row['derivedBirthdate'];
                $row['is_derived_birthdate'] = 1;
            }

            if (!empty($row['deformities'])) {
                $row['deformities'] = array_map('trim', explode(' ', $row['deformities']));
            }
            $row['animal_sireknown']=static::encodeBoolean($row['animal_sireknown']);
            $row['animal_damknown']=static::encodeBoolean($row['animal_damknown']);
            $insert_data[$k] = $row;
        }
        $targetModel = new Animal(['org_id' => $this->org_id]);
        $this->save($insert_data, $targetModel, true, ['tag_id' => '{tag_id}']);
    }

    /**
     * @param string $farmCode
     * @return bool|string
     * @throws \Exception
     */
    protected function getFarmId($farmCode)
    {
        $condition = '([[odk_code]]=:code';
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