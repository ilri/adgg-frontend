<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-24
 * Time: 8:48 PM
 */

namespace backend\modules\auth\forms;


use backend\modules\auth\models\Users;
use backend\modules\core\models\OrganizationUnits;
use common\excel\ExcelReaderTrait;
use common\excel\ImportInterface;
use common\helpers\Msisdn;
use common\models\Model;
use Yii;

class UploadUsers extends Model implements ImportInterface
{
    use ExcelReaderTrait;

    /**
     * @var int
     */
    public $org_id;

    /**
     * @var int
     */
    public $level_id;
    /**
     * @var int
     */
    public $role_id;

    /**
     * @var Users
     */
    public $_model;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->end_column = 'AD';

        $this->required_columns = [];
        $this->_model = new Users(['org_id' => $this->org_id, 'level_id' => $this->level_id, 'role_id' => $this->role_id]);
        foreach ($this->_model->getExcelColumns() as $column) {
            $this->file_columns['[' . $column . ']'] = $this->_model->getAttributeLabel($column);
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge($this->excelValidationRules(), [
            [['org_id', 'level_id', 'role_id'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
            'org_id' => $this->_model->getAttributeLabel('org_id'),
            'level_id' => $this->_model->getAttributeLabel('level_id'),
            'role_id' => $this->_model->getAttributeLabel('role_id'),
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

            $row['org_id'] = $this->org_id;
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

        $this->save($insert_data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function save($data)
    {
        if (empty($data))
            return false;

        $model = clone $this->_model;
        $model->auto_generate_password = 1;
        foreach ($data as $n => $row) {
            $newModel = Users::find()->andWhere([
                'username' => $row['username'],
                'org_id' => $row['org_id'],
            ])->one();

            if (null === $newModel) {
                $newModel = clone $model;
            }
            $newModel->setScenario(Users::SCENARIO_UPLOAD);
            $this->saveExcelRow($newModel, $row, $n);
        }

        if (!empty($this->_failedRows)) {
            foreach ($this->_failedRows as $log) {
                Yii::warning($log);
            }
        }
    }

    protected function cleanPhoneNumber($number)
    {
        return Msisdn::format($number, '255');
    }

    /**
     * @param string $regionCode
     * @return string
     * @throws \Exception
     */
    protected function getRegionId($regionCode)
    {
        return OrganizationUnits::getScalar('id', ['org_id' => $this->org_id, 'level' => OrganizationUnits::LEVEL_REGION, 'code' => $regionCode]);
    }

    /**
     * @param string $districtCode
     * @return string
     * @throws \Exception
     */
    protected function getDistrictId($districtCode)
    {
        return OrganizationUnits::getScalar('id', ['org_id' => $this->org_id, 'level' => OrganizationUnits::LEVEL_DISTRICT, 'code' => $districtCode]);
    }
}