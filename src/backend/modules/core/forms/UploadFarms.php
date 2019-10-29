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
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\helpers\Msisdn;
use console\jobs\JobInterface;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UploadFarms extends ExcelUploadForm implements ImportInterface, JobInterface
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
        $this->orgModel = Organization::loadModel($this->org_id);

        foreach ($batch as $k => $excel_row) {
            $row = $this->getExcelRowColumns($excel_row, $columns);
            if (empty($row)) {
                continue;
            }
            $row['org_id'] = $this->org_id;
            $row['reg_date'] = static::getDateColumnData($row['reg_date'], 'Y-m-d', null);
            $row['region_id'] = $this->getRegionId($row['region_code']);
            $row['district_id'] = $this->getDistrictId($row['district_code'], $row['region_id']);
            $row['ward_id'] = $this->getWardId($row['ward_code'], $row['district_id']);
            $row['village_id'] = $this->getVillageId($row['village_code'], $row['ward_id']);
            $row['field_agent_id'] = $this->getFieldAgentId($row['field_agent_code'], $row['field_agent_code2']);
            $row['code'] = $row['phone'];
            if (!empty($row['phone'])) {
                $row['phone'] = $this->cleanPhoneNumber($row['phone']);
            }

            $insert_data[$k] = $row;
        }
        $model = new Farm(['org_id' => $this->org_id]);
        $this->save($insert_data, $model, true, ['code' => '{code}', 'org_id' => $this->org_id]);
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
     * @param int|string $code
     * @param int $level
     * @param null $parentId
     * @return string
     * @throws \Exception
     */
    public function getAdminUnitId($code, $level, $parentId = null)
    {
        $code = trim($code);
        if (empty($code)) {
            return null;
        }
        $condition = ['org_id' => $this->org_id, 'level' => $level, 'code' => $code];
        if (!empty($parentId)) {
            $condition['parent_id'] = $parentId;
        }
        $id = OrganizationUnits::getScalar('id', $condition);
        if (empty($id) && !is_numeric($code)) {
            return null;
        }

        return $id;
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

    protected function cleanPhoneNumber($number)
    {
        return Msisdn::format($number, $this->orgModel->dialing_code);
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_FARM_DATA;
    }
}