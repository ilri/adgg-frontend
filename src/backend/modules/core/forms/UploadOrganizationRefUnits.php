<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-23
 * Time: 1:20 PM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\OrganizationRefUnits;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;
use yii\base\InvalidArgumentException;

class UploadOrganizationRefUnits extends ExcelUploadForm implements ImportInterface
{

    /**
     * @var int
     */
    public $country_id;

    /**
     * @var int
     */
    public $level;

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
            [['country_id', 'level'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
            'country_id' => 'Country',
            'level' => 'Level',
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
            $row['level'] = $this->level;

            if (!empty($row['parent_code'])) {
                $row['parent_id'] = $this->getParentId($row['parent_code']);
            }

            $insert_data[$k] = $row;
        }

        $model = new OrganizationRefUnits(['country_id' => $this->country_id, 'level' => $this->level]);
        $this->save($insert_data, $model, true, ['code' => '{code}', 'country_id' => $this->country_id, 'level' => $this->level]);
    }

    /**
     * @param string $code
     * @return string|null
     * @throws \Exception
     */
    protected function getParentId($code)
    {
        if ($this->level == OrganizationRefUnits::LEVEL_REGION) {
            return null;
        }
        $parentLevel = null;
        switch ($this->level) {
            case OrganizationRefUnits::LEVEL_DISTRICT:
                $parentLevel = OrganizationRefUnits::LEVEL_REGION;
                break;
            case OrganizationRefUnits::LEVEL_WARD:
                $parentLevel = OrganizationRefUnits::LEVEL_DISTRICT;
                break;
            case OrganizationRefUnits::LEVEL_VILLAGE:
                $parentLevel = OrganizationRefUnits::LEVEL_WARD;
                break;
            default:
                throw new InvalidArgumentException();
        }

        $parentId = OrganizationRefUnits::getScalar('id', ['country_id' => $this->country_id, 'level' => $parentLevel, 'code' => $code]);
        if (empty($parentId)) {
            return null;
        }
        return $parentId;
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_ORGANIZATION_REF_UNITS;
    }
}