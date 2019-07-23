<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-23
 * Time: 1:20 PM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\OrganizationUnits;
use common\excel\ExcelReaderTrait;
use common\excel\ImportInterface;
use common\models\Model;
use Yii;
use yii\base\InvalidArgumentException;

class UploadOrganizationUnits extends Model implements ImportInterface
{
    use ExcelReaderTrait;

    /**
     * @var int
     */
    public $org_id;

    /**
     * @var int
     */
    public $level;

    /**
     * @var OrganizationUnits
     */
    public $_model;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->end_column = 'Z';

        $this->required_columns = [];
        $this->_model = new OrganizationUnits(['org_id' => $this->org_id, 'level' => $this->level]);
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
            [['org_id', 'level'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
            'org_id' => $this->_model->getAttributeLabel('org_id'),
            'level' => $this->_model->getAttributeLabel('level'),
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
            $row['level'] = $this->level;

            if (!empty($row['parent_code'])) {
                $row['parent_id'] = $this->getParentId($row['parent_code']);
            }

            $insert_data[$k] = $row;
        }

        $this->save($insert_data);
    }

    /**
     * @param string $code
     * @return string|null
     * @throws \Exception
     */
    protected function getParentId($code)
    {
        if ($this->level == OrganizationUnits::LEVEL_REGION) {
            return null;
        }
        $parentLevel = null;
        switch ($this->level) {
            case OrganizationUnits::LEVEL_DISTRICT:
                $parentLevel = OrganizationUnits::LEVEL_REGION;
                break;
            case OrganizationUnits::LEVEL_WARD:
                $parentLevel = OrganizationUnits::LEVEL_DISTRICT;
                break;
            case OrganizationUnits::LEVEL_VILLAGE:
                $parentLevel = OrganizationUnits::LEVEL_WARD;
                break;
            default:
                throw new InvalidArgumentException();
        }

        $parentId = OrganizationUnits::getScalar('id', ['org_id' => $this->org_id, 'level' => $parentLevel, 'code' => $code]);
        if (empty($parentId)) {
            return null;
        }
        return $parentId;
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
            $newModel = OrganizationUnits::find()->andWhere([
                'code' => $row['code'],
                'org_id' => $row['org_id'],
                'level' => $row['level'],
            ])->one();

            if (null === $newModel) {
                $newModel = clone $model;
            }

            $this->saveExcelRaw($newModel, $row, $n);
        }

        if (!empty($this->_failedRows)) {
            foreach ($this->_failedRows as $log) {
                Yii::warning($log);
            }
        }
    }
}