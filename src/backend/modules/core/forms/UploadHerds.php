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
use backend\modules\core\models\Country;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;
use common\helpers\Lang;
use common\helpers\Msisdn;

class UploadHerds extends ExcelUploadForm implements ImportInterface
{
    /**
     * @var int
     */
    public $country_id;

    /**
     * @var Country
     */
    public $countryModel;

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
            [['country_id'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
            'country_id' => Lang::t('Country'),
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
        $this->countryModel = Country::loadModel($this->country_id);

        foreach ($batch as $k => $excel_row) {
            $row = $this->getExcelRowColumns($excel_row, $columns);
            if (empty($row)) {
                continue;
            }
            $row['country_id'] = $this->country_id;
            $row['reg_date'] = static::getDateColumnData($row['reg_date'], 'Y-m-d', 'UTC', 'd/m/Y');
            $insert_data[$k] = $row;
        }

        $model = new AnimalHerd(['country_id' => $this->country_id]);
        $this->save($insert_data, $model, true, ['' => '{}', 'country_id' => $this->country_id]);
    }

    protected function getFarmId($row)
    {
        $farmerPhone = trim($row['farmerPhone']);
        $farm_id = Farm::getScalar('id', ['code' => $farmerPhone, 'country_id' => $this->country_id]);

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
        $model->country_id = $row['country_id'];
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
        return Msisdn::format($number, $this->countryModel->dialing_code);
    }
}