<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-24
 * Time: 8:48 PM
 */

namespace backend\modules\auth\forms;


use backend\modules\auth\models\Users;
use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\CountryUnits;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;
use common\helpers\Msisdn;

class UploadUsers extends ExcelUploadForm implements ImportInterface
{
    /**
     * @var int
     */
    public $country_id;

    /**
     * @var int
     */
    public $level_id;
    /**
     * @var int
     */
    public $role_id;

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
            [['country_id', 'level_id', 'role_id'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
            'country_id' => 'Country',
            'level_id' => 'Level',
            'role_id' => 'Role',
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
            if (empty($row))
                continue;

            $row['country_id'] = $this->country_id;
            $row['level_id'] = $this->level_id;
            $row['role_id'] = $this->role_id;
            if (!empty($row['phone'])) {
                $row['phone'] = $this->cleanPhoneNumber($row['phone']);
            }

            if (!empty($row['region_code'])) {
                $row['region_id'] = $this->getRegionId($row['region_code']);
            }
            if (!empty($row['district_code'])) {
                $row['district_id'] = $this->getDistrictId($row['district_code']);
            }

            $insert_data[$k] = $row;
        }
        $model = new Users(['country_id' => $this->country_id, 'level_id' => $this->level_id, 'role_id' => $this->role_id]);
        $this->save($insert_data, $model, false, ['username' => '{username}', 'country_id' => $this->country_id]);
    }

    protected function cleanPhoneNumber($number)
    {
        return Msisdn::format($number, '254');
    }

    /**
     * @param string $regionCode
     * @return string
     * @throws \Exception
     */
    protected function getRegionId($regionCode)
    {
        return CountryUnits::getScalar('id', ['country_id' => $this->country_id, 'level' => CountryUnits::LEVEL_REGION, 'code' => $regionCode]);
    }

    /**
     * @param string $districtCode
     * @return string
     * @throws \Exception
     */
    protected function getDistrictId($districtCode)
    {
        return CountryUnits::getScalar('id', ['country_id' => $this->country_id, 'level' => CountryUnits::LEVEL_DISTRICT, 'code' => $districtCode]);
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_USERS;
    }
}