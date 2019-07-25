<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19 21:42
 */

namespace backend\modules\core\forms;


use backend\modules\auth\models\Users;
use backend\modules\core\models\Farm;
use backend\modules\core\models\OrganizationUnits;
use common\excel\ExcelReaderTrait;
use common\excel\ImportInterface;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\helpers\Msisdn;
use common\models\Model;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Yii;

class UploadFarms extends Model implements ImportInterface
{
    use ExcelReaderTrait;

    /**
     * @var Farm
     */
    private $_model;

    /**
     * @var int
     */
    public $org_id;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->end_column = 'AZ';

        $this->required_columns = [];
        $this->_model = new Farm(['org_id' => $this->org_id]);
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
            'region_id' => Lang::t('Region'),
            'district_id' => Lang::t('District'),
            'ward_id' => Lang::t('Ward'),
            'village_id' => Lang::t('Village'),
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
            Yii::info($row);
            if (empty($row))
                continue;
            if (!empty($row['reg_date'])) {
                if (is_numeric($row['reg_date'])) {
                    $row['reg_date'] = Date::excelToDateTimeObject($row['reg_date'])->format('Y-m-d');
                } else {
                    $row['reg_date'] = DateUtils::formatDate($row['reg_date'], 'Y-m-d', 'UTC');
                }
            }
            $row['org_id'] = $this->org_id;

            if (!empty($row['region_code'])) {
                $row['region_id'] = $this->getRegionId($row['region_code']);
            }
            if (!empty($row['district_code'])) {
                $row['district_id'] = $this->getDistrictId($row['district_code'], $row['region_id']);
            }
            if (!empty($row['ward_code'])) {
                $row['ward_id'] = $this->getWardId($row['ward_code'], $row['district_id']);
            }
            if (!empty($row['village_code'])) {
                $row['village_id'] = $this->getVillageId($row['village_code'], $row['ward_id']);
            }
            if (!empty($row['field_agent_code'])) {
                $row['field_agent_id'] = $this->getFieldAgentId($row['field_agent_code']);
            }
            if (!empty($row['phone'])) {
                $row['phone'] = $this->cleanPhoneNumber($row['phone']);
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
        foreach ($data as $n => $row) {
            $newModel = Farm::find()->andWhere([
                'code' => $row['code'],
                'org_id' => $row['org_id'],
            ])->one();

            if (null === $newModel) {
                $newModel = clone $model;
            }
            $newModel->setScenario(Farm::SCENARIO_UPLOAD);
            $this->saveExcelRaw($newModel, $row, $n);
        }

        if (!empty($this->_failedRows)) {
            foreach ($this->_failedRows as $log) {
                Yii::warning($log);
            }
        }
    }

    /**
     * @param $name
     * @return int|string|null
     * @throws \Exception
     */
    protected function getRegionId($name)
    {
        return $this->getAdminUnitId($name, OrganizationUnits::LEVEL_REGION);
    }

    /**
     * @param $name
     * @param int $regionId
     * @return int|string|null
     * @throws \Exception
     */
    protected function getDistrictId($name, $regionId)
    {
        return $this->getAdminUnitId($name, OrganizationUnits::LEVEL_DISTRICT, $regionId);
    }

    /**
     * @param $name
     * @param int $districtId
     * @return int|string|null
     * @throws \Exception
     */
    protected function getWardId($name, $districtId)
    {
        return $this->getAdminUnitId($name, OrganizationUnits::LEVEL_WARD, $districtId);
    }

    /**
     * @param $name
     * @param int $wardId
     * @return int|string|null
     * @throws \Exception
     */
    protected function getVillageId($name, $wardId)
    {
        return $this->getAdminUnitId($name, OrganizationUnits::LEVEL_VILLAGE, $wardId);
    }

    /**
     * @param int|string $name
     * @param int $level
     * @param null $parentId
     * @return string
     * @throws \Exception
     */
    public function getAdminUnitId($name, $level, $parentId = null)
    {
        $name = trim($name);
        if (empty($name)) {
            return null;
        }
        $condition = ['org_id' => $this->org_id, 'level' => $level];
        if (is_numeric($name)) {
            $condition = ['id' => $name];
        } else {
            $condition['name'] = $name;
        }
        $id = OrganizationUnits::getScalar('id', $condition);
        if (empty($id) && !is_numeric($name)) {
            $model = new OrganizationUnits(['org_id' => $this->org_id, 'level' => $level, 'name' => $name, 'parent_id' => $parentId]);
            $model->enableAuditTrail = false;
            $model->save();
            $id = $model->id;
        }

        return $id;
    }

    /**
     * @param int|string $code
     * @return string
     * @throws \Exception
     */
    public function getFieldAgentId($code)
    {
        $id = Users::getScalar('id', ['org_id' => $this->org_id, 'username' => $code]);
        if (empty($id)) {
            return null;
        }
        return $id;
    }

    protected function cleanPhoneNumber($number)
    {
        return Msisdn::format($number, '255');
    }
}