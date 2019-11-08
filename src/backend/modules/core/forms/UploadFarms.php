<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19 21:42
 */

namespace backend\modules\core\forms;


use backend\modules\auth\models\Users;
use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\Farm;
use backend\modules\core\models\Organization;
use backend\modules\core\models\OrganizationUnits;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;
use common\helpers\Lang;
use common\helpers\Msisdn;

class UploadFarms extends ExcelUploadForm implements ImportInterface
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
     * @var array
     */
    private $_regions;
    /**
     * @var array
     */
    private $_districts;
    /**
     * @var array
     */
    private $_wards;
    /**
     * @var array
     */
    private $_villages;

    public $farm_type;
    public $project;


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
            [['farm_type', 'project'], 'safe'],
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
            'farm_type' => Lang::t('Farm Type'),
            'project' => Lang::t('Project'),
        ]);
    }

    protected function setRegions()
    {
        if (null === $this->_regions) {
            $this->_regions = OrganizationUnits::getData(['id', 'code'], ['org_id' => $this->org_id, 'level' => OrganizationUnits::LEVEL_REGION]);
        }
    }

    protected function setDistricts()
    {
        if (null === $this->_districts) {
            $this->_districts = OrganizationUnits::getData(['id', 'code'], ['org_id' => $this->org_id, 'level' => OrganizationUnits::LEVEL_DISTRICT]);
        }
    }

    protected function setWards()
    {
        if (null === $this->_wards) {
            $this->_wards = OrganizationUnits::getData(['id', 'code'], ['org_id' => $this->org_id, 'level' => OrganizationUnits::LEVEL_WARD]);
        }
    }

    protected function setVillages()
    {
        if (null === $this->_villages) {
            $this->_villages = OrganizationUnits::getData(['id', 'code'], ['org_id' => $this->org_id, 'level' => OrganizationUnits::LEVEL_VILLAGE]);
        }
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
        $this->setRegions();
        $this->setDistricts();
        $this->setWards();
        $this->setVillages();

        foreach ($batch as $k => $excel_row) {
            $row = $this->getExcelRowColumns($excel_row, $columns);
            if (empty($row)) {
                continue;
            }
            $row['org_id'] = $this->org_id;
            $row['reg_date'] = static::getDateColumnData($row['reg_date'], 'Y-m-d');
            $row['region_id'] = $this->getRegionId($row['region_code']);
            $row['district_id'] = $this->getDistrictId($row['district_code']);
            $row['ward_id'] = $this->getWardId($row['ward_code']);
            $row['village_id'] = $this->getVillageId($row['village_code']);
            $row['field_agent_id'] = $this->getFieldAgentId($row['field_agent_code'], $row['field_agent_code2']);
            $row['phone'] = $this->preparePhoneNumber($row['phone']);
            $insert_data[$k] = $row;
        }
        $model = new Farm(['org_id' => $this->org_id, 'farm_type' => $this->farm_type, 'project' => $this->project, 'countryDialingCode' => $this->orgModel->dialing_code]);
        $this->save($insert_data, $model, false);
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
     * @return int|string|null
     * @throws \Exception
     */
    protected function getDistrictId($name)
    {
        return $this->getAdminUnitId($name, OrganizationUnits::LEVEL_DISTRICT);
    }

    /**
     * @param $name
     * @return int|string|null
     * @throws \Exception
     */
    protected function getWardId($name)
    {
        return $this->getAdminUnitId($name, OrganizationUnits::LEVEL_WARD);
    }

    /**
     * @param $name
     * @return int|string|null
     * @throws \Exception
     */
    protected function getVillageId($name)
    {
        return $this->getAdminUnitId($name, OrganizationUnits::LEVEL_VILLAGE);
    }

    /**
     * @param int|string $code
     * @param int $level
     * @return string
     * @throws \Exception
     */
    public function getAdminUnitId($code, $level)
    {
        $data = [];
        switch ($level) {
            case OrganizationUnits::LEVEL_REGION:
                $data = $this->_regions;
                break;
            case OrganizationUnits::LEVEL_DISTRICT:
                $data = $this->_districts;
                break;
            case OrganizationUnits::LEVEL_WARD:
                $data = $this->_wards;
                break;
            case OrganizationUnits::LEVEL_VILLAGE:
                $data = $this->_villages;
                break;
        }
        $search = \common\helpers\ArrayHelper::search($data, 'code', $code);
        return $search['id'] ?? null;
    }

    /**
     * @param int|string $code
     * @param int|string $code2
     * @return string
     * @throws \Exception
     */
    public function getFieldAgentId($code, $code2)
    {
        if (!empty($code)) {
            $id = Users::getScalar('id', ['org_id' => $this->org_id, 'username' => $code]);
        } else {
            $id = Users::getScalar('id', ['org_id' => $this->org_id, 'username' => $code2]);
        }
        if (empty($id)) {
            return null;
        }
        return $id;
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_FARM_DATA;
    }

    protected function preparePhoneNumber($phone)
    {
        if (empty($phone)) {
            return null;
        }

        $phone = explode('_', $phone);
        $phone = $phone[0];
        return (string)Msisdn::format($phone, $this->orgModel->dialing_code);
    }
}