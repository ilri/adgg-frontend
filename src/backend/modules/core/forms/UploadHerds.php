<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-14
 * Time: 10:14 PM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\AnimalHerd;
use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\Farm;
use backend\modules\core\models\Organization;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;
use common\helpers\Lang;
use common\helpers\Msisdn;

class UploadHerds extends ExcelUploadForm implements ImportInterface
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
            $row['reg_date'] = static::getDateColumnData($row['reg_date'], 'Y-m-d', 'UTC', 'd/m/Y');
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
        if (empty($data)) {
            return false;
        }

        $model = new AnimalHerd(['org_id' => $this->org_id]);
        $nMax = 0;
        foreach ($data as $n => $row) {
            $newModel = AnimalHerd::find()->andWhere([
                'herd_code' => $row['herd_code'],
                'org_id' => $row['org_id'],
            ])->one();

            if (null === $newModel) {
                $newModel = clone $model;
                $newModel->farm_id = $this->getFarmId($row);
            }
            $this->saveExcelRaw($newModel, $row, $n);
            $nMax = $n;
        }

        $this->updateCurrentProcessedRow($nMax);
    }

    protected function getFarmId($row)
    {
        $farmerPhone = trim($row['farmerPhone']);
        $farm_id = Farm::getScalar('id', ['code' => $farmerPhone, 'org_id' => $this->org_id]);

        if (!empty($farm_id)) {
            return $farm_id;
        }
        $model = new Farm(['scenario' => Farm::SCENARIO_UPLOAD]);
        $model->name = $row['name'];
        $model->farmer_name = $row['farmerName'];
        if (empty($model->name)) {
            if (!empty($model->farmer_name)) {
                $model->name = $model->farmer_name;
            } else {
                $model->name = 'NULL';
            }
        }
        if (empty($model->farmer_name)) {
            $model->farmer_name = $model->name;
        }
        $model->reg_date = $row['reg_date'];
        $model->code = $farmerPhone;
        $model->phone = $this->cleanPhoneNumber($farmerPhone);
        $model->email = $row['farmerEmail'];
        $model->latitude = $row['latitude'];
        $model->longitude = $row['longitude'];
        $model->org_id = $row['org_id'];
        $model->enableAuditTrail = false;
        $model->save(false);
        return $model->id;
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_HERD_DATA;
    }

    protected function cleanPhoneNumber($number)
    {
        return Msisdn::format($number, $this->orgModel->dialing_code);
    }
}