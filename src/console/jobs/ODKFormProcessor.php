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
use backend\modules\core\models\TableAttributeInterface;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\models\ActiveRecord;
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

    /**
     * @var int
     */
    private $_farmId;

    /**
     * @var string
     */
    private $_date;

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
                //farmer registration
                $this->registerNewFarmer();
            } else {
                $message = Lang::t('This Version ({old_version}) of ODK Form is currently not supported. Version ({version}) and above are supported.', ['old_version' => $this->_model->form_version, 'version' => self::MIN_SUPPORTED_ODK_FORM_VERSION]);
                $this->_model->error_message = $message;
                $this->_model->has_errors = 1;
                Yii::$app->controller->stdout("{$message}\n");
                Yii::$app->end();
            }

            $this->_model->is_processed = 1;
            $this->_model->processed_at = DateUtils::mysqlTimestamp();
            $this->_model->save(false);
            //ODKJsonNotification::createManualNotifications(ODKJsonNotification::NOTIF_ODK_JSON, $this->_model->id);
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }
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

            return $queue->push($obj);
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
        $code = $this->getFormDataValueByKey($this->_model->form_data, $jsonKey);
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
        $code = $this->getFormDataValueByKey($this->_model->form_data, $jsonKey);
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
        $code = $this->getFormDataValueByKey($this->_model->form_data, $jsonKey);
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
        $code = $this->getFormDataValueByKey($this->_model->form_data, $jsonKey);
        $id = CountryUnits::getScalar('id', ['code' => $code, 'country_id' => $this->_model->country_id, 'level' => CountryUnits::LEVEL_VILLAGE]);
        if (empty($id)) {
            $id = null;
        }
        $this->_villageId = $id;
    }

    /**
     * @return int
     */
    protected function getFarmId()
    {
        if (empty($this->_farmId)) {
            $this->setFarmId();
        }
        return $this->_farmId;
    }

    protected function setFarmId()
    {
        $jsonKey = 'activities_farmer';
        $code = $this->getFormDataValueByKey($this->_model->form_data, $jsonKey);
        $version1Point5 = OdkForm::getVersionNumber(OdkForm::ODK_FORM_VERSION_1_POINT_5);
        $formVersionNumber = OdkForm::getVersionNumber($this->_model->form_version);
        if (!empty($code) && $formVersionNumber <= $version1Point5) {
            //ver 1.5 and below:code=odk_farm_code
            $id = Farm::getScalar('id', ['odk_farm_code' => $code, 'country_id' => $this->_model->country_id]);
        } else {
            //ver 1.6 and above: code=id
            $id = $code;
        }
        if (empty($id)) {
            $id = null;
        }
        $this->_farmId = $id;
    }

    protected function getDate()
    {
        if (is_null($this->_date)) {
            $this->setDate();
        }
        return $this->_date;
    }

    protected function setDate()
    {
        if ($this->_model->form_version === OdkForm::ODK_FORM_VERSION_1_POINT_4) {
            $jsonKey = 'form_activity/form_datecollection';
        } else {
            $jsonKey = 'activities_location/form_datecollection';
        }
        $this->_date = $this->getFormDataValueByKey($this->_model->form_data, $jsonKey);
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

    /**
     * @param array $data
     * @param string $key
     * @return mixed|string|null
     */
    protected function getFormDataValueByKey(array $data, string $key)
    {
        $value = $data[$key] ?? null;
        if (is_string($value)) {
            $value = trim($value);
        }
        return $value;
    }

    protected function registerNewFarmer()
    {
        //farmer registration details as defined in ODK forms
        $farmersRepeatKey = 'farmer_general';
        $farmerVillageGroupKey = 'farmer_notevillage';
        $farmerGeneralDetailsGroupKey = 'farmer_generaldetails';
        $farmerHouseholdHeadGroupKey = 'farmer_hhheaddetails';

        //attributes keys
        $villageCodeKey = self::getAttributeJsonKey('farmer_village', $farmerVillageGroupKey, $farmersRepeatKey);
        $farmTypeKey = self::getAttributeJsonKey('farmer_farmtype', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerFirstNameKey = self::getAttributeJsonKey('farmer_firstname', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerOtherNamesKey = self::getAttributeJsonKey('farmer_othnames', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerCodeKey = self::getAttributeJsonKey('farmer_uniqueid', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerPhoneKey = self::getAttributeJsonKey('farmer_mobile', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerGenderKey = self::getAttributeJsonKey('farmer_gender', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerIsHouseholdHeadKey = self::getAttributeJsonKey('farmer_hhhead', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $locationStringKey = $farmersRepeatKey . '/farmer_gpslocation';
        //household head


        $farmersData = $this->_model->form_data[$farmersRepeatKey] ?? null;
        if (null === $farmersData) {
            return;
        }
        $farmerModel = new Farm([
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
            'field_agent_id' => $this->_model->user_id,
            'reg_date' => $this->getDate(),
        ]);
        foreach ($farmersData as $k => $farmerData) {
            $newFarmerModel = clone $farmerModel;
            //get village group fields
            $villageCode = $this->getFormDataValueByKey($farmerData, $villageCodeKey);
            if (!empty($villageCode)) {
                $villageModel = CountryUnits::find()->andWhere(['code' => $villageCode, 'level' => CountryUnits::LEVEL_VILLAGE, 'country_id' => $this->_model->country_id])->one();
                if (null !== $villageModel) {
                    $newFarmerModel->village_id = $villageModel->id;
                    $newFarmerModel->ward_id = $villageModel->parent_id;
                    $newFarmerModel->district_id = $newFarmerModel->ward->parent_id ?? null;
                    $newFarmerModel->region_id = $newFarmerModel->district->parent_id ?? null;
                }
            }
            $newFarmerModel->farm_type = $this->getFormDataValueByKey($farmerData, $farmTypeKey);
            $firstName = $this->getFormDataValueByKey($farmerData, $farmerFirstNameKey);
            $otherNames = $this->getFormDataValueByKey($farmerData, $farmerOtherNamesKey);
            $name = trim($firstName . ' ' . $otherNames);
            $newFarmerModel->name = $name;
            $newFarmerModel->farmer_name = $name;
            $newFarmerModel->code = $this->getFormDataValueByKey($farmerData, $farmerCodeKey);
            $newFarmerModel->phone = $this->getFormDataValueByKey($farmerData, $farmerPhoneKey);
            $newFarmerModel->gender_code = $this->getFormDataValueByKey($farmerData, $farmerGenderKey);
            $newFarmerModel->farmer_is_hh_head = $this->getFormDataValueByKey($farmerData, $farmerIsHouseholdHeadKey);
            $geoLocation = static::splitGPRSLocationString($this->getFormDataValueByKey($farmerData, $locationStringKey));
            $newFarmerModel->latitude = $geoLocation['latitude'];
            $newFarmerModel->longitude = $geoLocation['longitude'];
            $newFarmerModel->setDynamicAttributesValuesFromOdkForm($farmersData, $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
            $newFarmerModel->setDynamicAttributesValuesFromOdkForm($farmersData, $farmerHouseholdHeadGroupKey, $farmersRepeatKey);
            $this->saveDataModel($newFarmerModel, $k, true);
        }
    }

    /**
     * @param ActiveRecord|TableAttributeInterface $model
     * @param string|int $index
     * @param bool $validate
     * @return ActiveRecord|TableAttributeInterface
     */
    protected function saveDataModel($model, $index, $validate = true)
    {
        $model->ignoreAdditionalAttributes = false;
        //$isSaved = $model->save($validate);//@todo uncomment after test
        $isSaved = $model->validate();//@todo remove after test
        $this->_model->farm_data[$index] = [
            'attributes' => $model->attributes,
            'errors' => $isSaved ? null : $model->getErrors(),
        ];

        return $model;
    }

    /**
     * @param string $attributeKey
     * @param string|null $groupKey
     * @param string|null $repeatKey
     * @return string
     */
    public static function getAttributeJsonKey($attributeKey, $groupKey = null, $repeatKey = null)
    {
        $key = '';
        if (!empty($repeatKey)) {
            $key = $repeatKey . '/';
        }
        if (!empty($groupKey)) {
            $key .= $groupKey . '/';
        }
        $key .= $attributeKey;

        return $key;
    }

    /**
     * @param string $locationString
     * @return array
     */
    public static function splitGPRSLocationString($locationString)
    {
        if (empty($locationString)) {
            $locationString = '';
        }
        //format: latitude longitude altitude accuracy
        //sample: -1.8350533333333332 37.325248333333334 1667.2 2.6
        $arr = array_map('trim', explode(' ', $locationString));

        return [
            'latitude' => $arr[0] ?? null,
            'longitude' => $arr[1] ?? null,
            'altitude' => $arr[2] ?? null,
            'accuracy' => $arr[3] ?? null,
        ];
    }

}