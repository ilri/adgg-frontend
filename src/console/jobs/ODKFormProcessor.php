<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-08
 * Time: 7:42 AM
 */

namespace console\jobs;


use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Farm;
use backend\modules\core\models\OdkForm;
use common\helpers\DateUtils;
use common\helpers\Lang;
use Yii;
use yii\base\BaseObject;
use yii\queue\Queue;

class ODKFormProcessor extends BaseObject implements JobInterface
{

    /**
     * @var int
     */
    public $itemId;

    /**
     * @var array
     */
    private $_jsonArr;

    /**
     * @var OdkForm
     */
    private $_model;

    /**
     * @var int
     */
    private $_regionId;

    /**
     * @var int
     */
    private $_districtId;

    /**
     * @var int
     */
    private $_wardId;

    /**
     * @var int
     */
    private $_villageId;

    const MIN_SUPPORTED_ODK_FORM_VERSION = OdkForm::ODK_FORM_VERSION_1_POINT_4;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        $this->_model = OdkForm::find()->andWhere(['id' => $this->itemId])->one();
        if ($this->_model === null) {
            return false;
        }
        try {
            if (is_string($this->_model->form_data)) {
                $this->_model->form_data = json_decode($this->_model->form_data, true);
            }
            //check the version
            if ($this->isSupportedVersion()) {
                $this->setRegionId();
                $this->setDistrictId();
                $this->setWardId();
                $this->setVillageId();

                $this->_model->is_processed = 1;
                $this->_model->processed_at = DateUtils::mysqlTimestamp();
            } else {
                $message = Lang::t('This Version ({old_version}) of ODK Form is currently not supported. Version ({version}) and above are supported.', ['old_version' => $this->_model->form_version, 'version' => self::MIN_SUPPORTED_ODK_FORM_VERSION]);
                $this->_model->error_message = $message;
                Yii::$app->controller->stdout("{$message}\n");
                Yii::$app->end();
            }

            $this->_model->save(false);
            //ODKJsonNotification::createManualNotifications(ODKJsonNotification::NOTIF_ODK_JSON, $this->_model->id);
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }
    }

    protected function processFarmerIndividual()
    {
        $farmerCode = $this->_jsonArr['farmer_individual/activities_farmer'] ?? null;
        if ($farmerCode === null) {
            Yii::info('Farmer Individual Check Failed');
        }

        $farmModel = Farm::find()->andWhere(['code' => $farmerCode])->one();
        if ($farmModel === null) {
            $this->_model->has_errors = 1;
            $this->_model->error_message = Lang::t('Could not find the farm/farmer with {code_label} {code}', [
                'code_label' => (new Farm())->getAttributeLabel('code'),
                'code' => $farmerCode,
            ]);
            return false;
        }

        $attributes = [];
        foreach ($this->_jsonArr as $k => $value) {
            $attribute = $this->extractAttributesFromJson($k, $value);
            if (!empty($attribute)) {
                $attributes[] = $attribute;
            }

        }

        foreach ($attributes as $attribute => $value) {
            if (is_array($value)) {
                foreach ($value as $attr1 => $val1) {
                    if (is_array($val1)) {
                        foreach ($val1 as $attr2 => $val2) {
                            foreach ($val2 as $attr3 => $val3) {
                                if ($farmModel->hasAttribute($attr3)) {
                                    $farmModel->{$attr3} = $val3;
                                }
                            }
                        }
                    } else {
                        if ($farmModel->hasAttribute($attr1)) {
                            $farmModel->{$attr1} = $val1;
                        }
                    }
                }
            } else {
                if ($farmModel->hasAttribute($attribute)) {
                    $farmModel->{$attribute} = $value;
                }
            }
        }

        if ($farmModel->save()) {
            $this->_model->has_errors = 0;
        } else {
            $this->_model->has_errors = 1;
            $this->_model->error_message = json_encode($farmModel->getErrors());
        }
    }

    protected function extractAttributesFromJson($key, $value)
    {
        $attributes = [];
        if (!is_array($value)) {
            $k_arr = explode('/', $key);
            if ($k_arr[0] === 'farmer_individual') {
                $attribute = null;
                if (count($k_arr) > 1) {
                    $attribute = end($k_arr);
                } else {
                    $attribute = $k_arr[0];
                }
                $attribute = trim($attribute);
                if ($attribute !== null) {
                    $attributes[$attribute] = $value;
                }
            }
            return $attributes;
        } else {
            foreach ($value as $k => $v) {
                $attribute = $this->extractAttributesFromJson($k, $v);
                if (!empty($attribute)) {
                    $attributes[] = $attribute;
                }
            }
        }

        return $attributes;
    }

    /**
     * @param mixed $params
     * @return mixed
     */
    public static function push($params)
    {
        try {
            /* @var $queue \yii\queue\cli\Queue */
            $queue = Yii::$app->queue;
            if ($params instanceof self) {
                $obj = $params;
            } else {
                $obj = new self($params);
            }

            $id = $queue->push($obj);

            return $id;
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }
    }

    /**
     * @return int
     */
    protected function getRegionId()
    {
        if (empty($this->_regionId)) {
            $this->setRegionId();
        }
        return $this->_regionId;
    }


    protected function setRegionId()
    {
        $jsonKey = 'activities_location/activities_region';;
        $code = $this->_model->form_data[$jsonKey] ?? null;
        $id = CountryUnits::getScalar('id', ['code' => $code, 'country_id' => $this->_model->country_id, 'level' => CountryUnits::LEVEL_REGION]);
        if (empty($id)) {
            $id = null;
        }
        $this->_regionId = $id;
    }

    /**
     * @return int
     */
    protected function getDistrictId()
    {
        if (empty($this->_districtId)) {
            $this->setDistrictId();
        }
        return $this->_districtId;
    }


    protected function setDistrictId()
    {
        $jsonKey = 'activities_location/activities_zone';
        $code = $this->_model->form_data[$jsonKey] ?? null;
        $id = CountryUnits::getScalar('id', ['code' => $code, 'country_id' => $this->_model->country_id, 'level' => CountryUnits::LEVEL_DISTRICT]);
        if (empty($id)) {
            $id = null;
        }
        $this->_districtId = $id;
    }

    /**
     * @return int
     */
    protected function getWardId()
    {
        if (empty($this->_wardId)) {
            $this->setWardId();
        }
        return $this->_wardId;
    }


    protected function setWardId()
    {
        $jsonKey = 'activities_location/activities_ward';
        $code = $this->_model->form_data[$jsonKey] ?? null;
        $id = CountryUnits::getScalar('id', ['code' => $code, 'country_id' => $this->_model->country_id, 'level' => CountryUnits::LEVEL_WARD]);
        if (empty($id)) {
            $id = null;
        }
        $this->_wardId = $id;
    }

    /**
     * @return int
     */
    protected function getVillageId()
    {
        if (empty($this->_villageId)) {
            $this->setVillageId();
        }
        return $this->_villageId;
    }


    protected function setVillageId()
    {
        $jsonKey = 'activities_village';
        $code = $this->_model->form_data[$jsonKey] ?? null;
        $id = CountryUnits::getScalar('id', ['code' => $code, 'country_id' => $this->_model->country_id, 'level' => CountryUnits::LEVEL_VILLAGE]);
        if (empty($id)) {
            $id = null;
        }
        $this->_villageId = $id;
    }

    /**
     * @return bool
     */
    protected function isSupportedVersion()
    {
        $formVersionNumber = OdkForm::getVersionNumber($this->_model->form_version);
        $minSupportedVersionNumber = OdkForm::getVersionNumber(self::MIN_SUPPORTED_ODK_FORM_VERSION);
        return ($formVersionNumber >= $minSupportedVersionNumber);
    }

    //##### activities #######

    protected function activityCreateAdminAreas()
    {
        //@todo:
    }

    protected function activityRegisterUsers()
    {
        //@todo
    }

    protected function activityCreateVouchers()
    {
        //@todo
    }

    protected function activityRegisterFarmer()
    {
        //@todo
    }

    protected function activityRegisterFarmerHouseholdMembers()
    {
        //@todo
    }

    protected function activityRegisterAnimals()
    {
        //@todo
    }

    protected function activityRegisterFarmerTechnologyMobilization()
    {
        //@todo
    }

    protected function activityRecordAnimalSynchronizationEvent()
    {
        //@todo
    }

    protected function activityRecordAnimalInseminationEvent()
    {
        //@todo
    }

    protected function activityRecordAnimalPregnancyDiagnosisEvent()
    {
        //@todo
    }

    protected function activityRecordAnimalMilkProductionEvent()
    {
        //@todo
    }

}