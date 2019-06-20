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
use common\helpers\Lang;
use common\models\Model;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Yii;

class UploadFarms extends Model implements ImportInterface
{
    use ExcelReaderTrait;

    /**
     * @var Farm
     */
    public $clientModel;

    /**
     * @var int
     */
    public $org_id;
    /**
     * @var int
     */
    public $region_id;
    /**
     * @var int
     */
    public $district_id;
    /**
     * @var int
     */
    public $ward_id;
    /**
     * @var int
     */
    public $village_id;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->end_column = 'Z';

        $this->required_columns = [];
        $this->clientModel = new Farm();
        foreach ($this->clientModel->getExcelColumns() as $column) {
            $this->file_columns['[' . $column . ']'] = $this->clientModel->getAttributeLabel($column);
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
            [['region_id', 'district_id', 'ward_id', 'village_id'], 'safe'],
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
            if (empty($row))
                continue;
            if (!empty($row['reg_date']) && is_numeric($row['reg_date'])) {
                $row['reg_date'] = Date::excelToDateTimeObject($row['reg_date'])->format('Y-m-d');
            }
            $row['org_id'] = $this->org_id;
            $row['region_id'] = $this->region_id;
            $row['district_id'] = $this->district_id;
            $row['ward_id'] = $this->ward_id;
            $row['village_id'] = $this->village_id;

            if (empty($this->region_id) && !empty($row['region'])) {
                $row['region_id'] = $this->getRegionId($row['region']);
            }
            if (empty($this->district_id) && !empty($row['district'])) {
                $row['district_id'] = $this->getDistrictId($row['district'], $row['region_id']);
            }
            if (empty($this->ward_id) && !empty($row['ward'])) {
                $row['ward_id'] = $this->getWardId($row['ward'], $row['district_id']);
            }
            if (empty($this->village_id) && !empty($row['village'])) {
                $row['village_id'] = $this->getVillageId($row['village'], $row['ward_id']);
            }
            if (!empty($row['field_agent_name'])) {
                $row['field_agent_id'] = $this->getFieldAgentId($row['field_agent_name']);
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

        $model = new Farm([]);
        foreach ($data as $n => $row) {
            $newModel = Farm::find()->andWhere([
                'name' => $row['name'],
                'org_id' => $row['org_id'],
                'region_id' => $row['region_id'],
                'district_id' => $row['district_id'],
                'ward_id' => $row['ward_id'],
                'village_id' => $row['village_id'],
            ])->one();

            if (null === $newModel) {
                $newModel = clone $model;
            }
            foreach ($row as $k => $v) {
                if ($newModel->hasAttribute($k)) {
                    $newModel->{$k} = $v;
                }
            }
            $newModel->enableAuditTrail = false;
            if ($newModel->save()) {
                $this->_savedRows[$n] = $n;
            } else {
                $errors = $newModel->getFirstErrors();
                $this->_failedRows[$n] = Lang::t('Row {n}: {error}', ['n' => $n, 'error' => implode(', ', $errors)]);
            }
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
     * @param int|string $name
     * @return string
     * @throws \Exception
     */
    public function getFieldAgentId($name)
    {
        $name = trim($name);
        if (empty($name)) {
            return null;
        }
        $attributes = explode('-', $name);
        $phone = trim($attributes[0]);
        $name = $attributes[1] ?? null;
        $name = trim($name);
        $condition = ['phone' => $phone, 'name' => $name];
        $id = Users::getScalar('id', $condition);
        if (empty($id)) {
            return null;
        }
        return $id;
    }
}