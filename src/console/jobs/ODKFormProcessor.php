<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-08
 * Time: 7:42 AM
 */

namespace console\jobs;


use backend\modules\core\models\AIEvent;
use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Farm;
use backend\modules\core\models\FarmMetadata;
use backend\modules\core\models\FarmMetadataHouseholdMembers;
use backend\modules\core\models\FarmMetadataTechnologyMobilization;
use backend\modules\core\models\OdkForm;
use backend\modules\core\models\SyncEvent;
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

    /**
     * @var array
     */
    private $_farmData;

    /**
     * @var Farm
     */
    private $_farmModel;
    /**
     * @var array
     */
    private $_farmMetadata;

    /**
     * @var FarmMetadata[]
     */
    private $_farmMetadataModels;
    /**
     * @var array
     */
    private $_animalsData;

    /**
     * @var Animal[]
     */
    private $_animalsModels;
    /**
     * @var array
     */
    private $_animalEventsData;

    /**
     * @var AnimalEvent[]
     */
    private $_animalEventModels;

    const MIN_SUPPORTED_ODK_FORM_VERSION = OdkForm::ODK_FORM_VERSION_1_POINT_5;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        $this->_model = OdkForm::find()->andWhere(['id' => $this->itemId])->one();
        if ($this->_model === null) {
            Yii::$app->controller->stdout("No ODK form found with id: {$this->itemId}\n");
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
                //farm metadata
                $this->registerFarmerTechnologyMobilization();
                //animal registration
                $this->registerNewCattle();
                //animal events
                $this->registerAnimalSynchronization();
                $this->registerAnimalAI();
                $this->registerAnimalPD();
                $this->registerAnimalMilk();
                $this->registerAnimalVaccination();
                $this->registerAnimalParasiteInfection();
                $this->registerAnimalInjury();
                $this->registerAnimalMeasureDetails();
                $this->registerAnimalHoofHealth();
                $this->registerAnimalHoofTreatment();
                $this->registerAnimalFeedProvided();
                $this->registerAnimalGrowth();
                $this->registerCalfVaccination();
                $this->registerCalfParasiteInfection();
            } else {
                $message = Lang::t('This Version ({old_version}) of ODK Form is currently not supported. Version ({version}) and above are supported.', ['old_version' => $this->_model->form_version, 'version' => self::MIN_SUPPORTED_ODK_FORM_VERSION]);
                $this->_model->error_message = $message;
                $this->_model->has_errors = 1;
                Yii::$app->controller->stdout("{$message}\n");
                Yii::$app->end();
            }

            $this->_model->is_processed = 1;
            $this->_model->processed_at = DateUtils::mysqlTimestamp();
            if (!empty($this->_farmData)) {
                $this->_model->farm_data = $this->_farmData;
            }
            if (!empty($this->_farmMetadata)) {
                $this->_model->farm_metadata = $this->_farmMetadata;
            }
            if (!empty($this->_animalsData)) {
                $this->_model->animals_data = $this->_animalsData;
            }
            if (!empty($this->_animalEventsData)) {
                $this->_model->animal_events_data = $this->_animalEventsData;
            }
            $this->_model->save(false);
            //ODKJsonNotification::createManualNotifications(ODKJsonNotification::NOTIF_ODK_JSON, $this->_model->id);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $trace = $e->getTraceAsString();
            Yii::$app->controller->stdout("{$message}\n");
            Yii::$app->controller->stdout("{$trace}\n");
            Yii::error($message);
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

    /**
     * @return Farm
     */
    protected function getFarmModel()
    {
        if (null === $this->_farmModel) {
            $this->_farmModel = Farm::find()->andWhere(['id' => $this->getFarmId()])->one();
        }
        return $this->_farmModel;
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
        $jsonKey = 'activities_location/form_datecollection';
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
            $newFarmerModel->setDynamicAttributesValuesFromOdkForm($farmerData, $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
            $newFarmerModel->setDynamicAttributesValuesFromOdkForm($farmerData, $farmerHouseholdHeadGroupKey, $farmersRepeatKey);
            //Household Members (No Demographics)
            $newFarmerModel = $this->setFarmerHouseholdMembersNumbersAttributes($newFarmerModel, $k);

            $this->saveFarmModel($newFarmerModel, $k, true);
        }

        //Household Members (Full Demographics)
        $this->registerFarmerHouseholdMembers();

    }

    protected function registerFarmerHouseholdMembers()
    {
        //@todo: pending test
        $repeatKey = 'farmer_hhroaster';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (null === $data) {
            return;
        }
        $householdMemberRepeatKey = $repeatKey . '/farmer_hhmember';
        $householdMemberDetailsGroupKey = 'farmer_hhmemberdetails';
        foreach ($data as $k => $householderMembers) {
            $farmId = $this->getFarmId();
            $householderMembersData = $householderMembers[$householdMemberRepeatKey] ?? null;
            if (null === $householderMembersData) {
                continue;
            }
            $model = new FarmMetadataHouseholdMembers([
                'farm_id' => $farmId,
                'type' => FarmMetadataHouseholdMembers::TYPE_HOUSEHOLD_MEMBERS,
                'country_id' => $this->_model->country_id,
                'odk_form_uuid' => $this->_model->form_uuid,
            ]);
            foreach ($householderMembersData as $k2 => $householderMember) {
                $newModel = clone $model;
                $newModel->setDynamicAttributesValuesFromOdkForm($householderMember, $householdMemberDetailsGroupKey, $householdMemberRepeatKey);
                $i = $k . '_' . $k2;
                $this->saveFarmMetadataModel($newModel, $i, true);
            }
        }
    }

    /**
     * @param Farm $farmerModel
     * @param string|int $index
     * @return Farm
     */
    protected function setFarmerHouseholdMembersNumbersAttributes($farmerModel, $index)
    {
        //@todo pending test
        // $farmer
        $repeatKey = 'farmer_hhmemberscount';
        $householdMembersNumberGroupKey = 'farmer_hhmemberno';
        $data = $this->_model->form_data[$repeatKey][$index] ?? null;
        if (null === $data) {
            return $farmerModel;
        }
        $farmerModel->setDynamicAttributesValuesFromOdkForm($data, $householdMembersNumberGroupKey, $repeatKey);

        return $farmerModel;
    }

    protected function registerNewCattle()
    {
        $repeatKey = 'animal_general';
        $animalIdentificationGroupKey = 'animal_identification';
        $animalsData = $this->_model->form_data[$repeatKey] ?? null;
        if (null === $animalsData) {
            return;
        }

        $farmModel = $this->getFarmModel();
        if (null === $farmModel) {
            $message = 'ODK Form processor: Register New Cattle aborted. Farm cannot be null. Form UUID: ' . $this->_model->form_uuid;
            Yii::error($message);
            return;
        }
        $animalModel = new Animal([
            'farm_id' => $farmModel->id,
            'country_id' => $farmModel->country_id,
            'region_id' => $farmModel->region_id,
            'district_id' => $farmModel->district_id,
            'ward_id' => $farmModel->ward_id,
            'village_id' => $farmModel->village_id,
            'org_id' => $farmModel->org_id,
            'client_id' => $farmModel->client_id,
            'odk_form_uuid' => $this->_model->form_uuid,
            'latitude' => $farmModel->latitude,
            'longitude' => $farmModel->longitude,
            'reg_date' => $this->getDate(),
        ]);

        $fixedAttributesMap = [
            'name' => self::getAttributeJsonKey('animal_name', $animalIdentificationGroupKey, $repeatKey),
            'tag_id' => self::getAttributeJsonKey('animal_tagid', $animalIdentificationGroupKey, $repeatKey),
            'animal_type' => self::getAttributeJsonKey('animal_type', $animalIdentificationGroupKey, $repeatKey),
            'animal_photo' => self::getAttributeJsonKey('animal_photo', $animalIdentificationGroupKey, $repeatKey),
            'main_breed' => self::getAttributeJsonKey('animal_mainbreed', $animalIdentificationGroupKey, $repeatKey),
            'breed_composition' => self::getAttributeJsonKey('animal_maincomp', $animalIdentificationGroupKey, $repeatKey),
            'birthdate' => self::getAttributeJsonKey('animal_actualdob', $animalIdentificationGroupKey, $repeatKey),
        ];
        $n = 1;
        foreach ($animalsData as $k => $animalData) {
            $newAnimalModel = clone $animalModel;
            foreach ($fixedAttributesMap as $attr => $odkKey) {
                $newAnimalModel->{$attr} = $this->getFormDataValueByKey($animalData, $odkKey);
            }
            $newAnimalModel->setDynamicAttributesValuesFromOdkForm($animalData, $animalIdentificationGroupKey, $repeatKey);
            $damModel = $this->getOrRegisterAnimalDam($animalData, $farmModel, $k);
            if (null !== $damModel) {
                $newAnimalModel->dam_id = $damModel->id;
                $newAnimalModel->dam_tag_id = $damModel->tag_id;
            }
            $sireModel = $this->getOrRegisterAnimalSire($animalData, $farmModel, $k);
            if (null !== $sireModel) {
                $newAnimalModel->sire_id = $sireModel->id;
                $newAnimalModel->sire_tag_id = $sireModel->tag_id;
                $newAnimalModel->sire_type = $sireModel->animal_type == 5 ? 1 : 2;
            }
            $i = 'N-' . $n;
            $this->saveAnimalModel($newAnimalModel, $i, true);
            $newAnimalModel = $this->_animalsModels[$i];
            $n++;
            //calving status
            $calvingRepeatKey = $repeatKey . '/animal_calfstatus';
            $calvingsData = $animalData[$calvingRepeatKey] ?? null;
            $eventModel = new CalvingEvent([
                'animal_id' => $newAnimalModel->id,
                'event_type' => CalvingEvent::EVENT_TYPE_CALVING,
                'country_id' => $newAnimalModel->country_id,
                'region_id' => $newAnimalModel->region_id,
                'district_id' => $newAnimalModel->district_id,
                'ward_id' => $newAnimalModel->ward_id,
                'village_id' => $newAnimalModel->village_id,
                'org_id' => $newAnimalModel->org_id,
                'client_id' => $newAnimalModel->client_id,
                'data_collection_date' => $this->getDate(),
                'event_date' => $this->getDate(),//Noted issue: No calving date in ODK form
                'latitude' => $newAnimalModel->latitude,
                'longitude' => $newAnimalModel->longitude,
                'field_agent_id' => $this->_model->user_id,
                'odk_form_uuid' => $this->_model->form_uuid,
            ]);
            if (!empty($calvingsData)) {
                foreach ($calvingsData as $i => $calvingData) {
                    $newEventModel = clone $eventModel;
                    $newEventModel->setDynamicAttributesValuesFromOdkForm($calvingData, 'animal_calfregistration', $calvingRepeatKey);
                    $this->saveAnimalEventModel($newEventModel, $i, true);
                }
            }
        }
    }

    protected function registerFarmerTechnologyMobilization()
    {
        //todo pending tests
        $repeatKey = 'farmer_techmobilization';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $model = new FarmMetadataTechnologyMobilization([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadataTechnologyMobilization::TYPE_TECHNOLOGY_MOBILIZATION,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        foreach ($data as $k => $datum) {
            $newModel = clone $model;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'farmer_techmobilizationdetails', $repeatKey);
            $i = 'technology_mobilization_' . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
        }

    }

    protected function registerAnimalSynchronization()
    {
        //todo pending tests
        $repeatKey = 'animal_breeding';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        $syncRepeatKey = $repeatKey . '/animal_breedingsync';
        $syncGroupKey = 'breeding_syncdetails';
        $animalCodeAttributeKey = self::getAttributeJsonKey('breeding_syncanimalcode', '', $syncRepeatKey);
        $eventDateKey = self::getAttributeJsonKey('breeding_syncservedate', $syncGroupKey, $syncRepeatKey);

        $this->registerAnimalEvent($data, AnimalEvent::EVENT_TYPE_SYNCHRONIZATION, $syncRepeatKey, $syncGroupKey, $animalCodeAttributeKey, $eventDateKey);
    }

    protected function registerAnimalAI()
    {
        //todo pending tests
        $repeatKey = 'animal_breeding';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        $aiRepeatKey = $repeatKey . '/animal_breedingai';
        $aiGroupKey = 'breeding_aidetails';
        $animalCodeAttributeKey = self::getAttributeJsonKey('breeding_aianimalcode', '', $aiRepeatKey);
        $eventDateKey = self::getAttributeJsonKey('breeding_aidate', $aiGroupKey, $aiRepeatKey);

        $this->registerAnimalEvent($data, AnimalEvent::EVENT_TYPE_AI, $aiRepeatKey, $aiGroupKey, $animalCodeAttributeKey, $eventDateKey);
    }

    protected function registerAnimalPD()
    {
        //todo pending tests
        $repeatKey = 'animal_breeding';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        $pdRepeatKey = $repeatKey . '/animal_breedingpd';
        $pdGroupKey = 'breeding_pdresults';
        $animalCodeAttributeKey = self::getAttributeJsonKey('breeding_pdanimalcode', '', $pdRepeatKey);
        $eventDateKey = self::getAttributeJsonKey('breeding_pdservicedate', $pdGroupKey, $pdRepeatKey);

        $this->registerAnimalEvent($data, AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS, $pdRepeatKey, $pdGroupKey, $animalCodeAttributeKey, $eventDateKey);
    }

    protected function registerAnimalMilk()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCowMonitoringParams();
        $groupKey = 'milk_prodanimal';
        $eventDateAttributeKey = self::getAttributeJsonKey('milk_milkdate', $groupKey, $repeatKey);
        $this->registerAnimalEvent($rawData, AnimalEvent::EVENT_TYPE_MILKING, $repeatKey, $groupKey, $animalCodeAttributeKey, $eventDateAttributeKey);
    }

    protected function registerAnimalVaccination()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCowMonitoringParams();
        $groupKey = 'vacc_vaccination';
        $eventDateAttributeKey = self::getAttributeJsonKey('vacc_vaccinedate', $groupKey, $repeatKey);
        $this->registerAnimalEvent($rawData, AnimalEvent::EVENT_TYPE_VACCINATION, $repeatKey, $groupKey, $animalCodeAttributeKey, $eventDateAttributeKey);
    }

    protected function registerAnimalParasiteInfection()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCowMonitoringParams();
        $groupKey = 'parasite_infection';
        $eventDateAttributeKey = self::getAttributeJsonKey('parasite_date', $groupKey, $repeatKey);
        $this->registerAnimalEvent($rawData, AnimalEvent::EVENT_TYPE_PARASITE_INFECTION, $repeatKey, $groupKey, $animalCodeAttributeKey, $eventDateAttributeKey);
    }

    protected function registerAnimalInjury()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCowMonitoringParams();
        $groupKey = 'injury_physical';
        $eventDateAttributeKey = self::getAttributeJsonKey('injury_date', $groupKey, $repeatKey);
        $this->registerAnimalEvent($rawData, AnimalEvent::EVENT_TYPE_INJURY, $repeatKey, $groupKey, $animalCodeAttributeKey, $eventDateAttributeKey);
    }

    protected function registerAnimalMeasureDetails()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCowMonitoringParams();
        $groupKey = 'measure_details';

        if (null === $rawData) {
            return;
        }

        $model = new AnimalEvent([
            'event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS,
            'data_collection_date' => $this->getDate(),
            'field_agent_id' => $this->_model->user_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        $heartgirthAttributeKey = self::getAttributeJsonKey('measure_heartgirth', $groupKey, $repeatKey);
        $weightAttributeKey = self::getAttributeJsonKey('measure_weight', $groupKey, $repeatKey);
        $bodyscoreAttributeKey = self::getAttributeJsonKey('measure_bodyscore', $groupKey, $repeatKey);
        foreach ($rawData as $k => $dataPoints) {
            $dataPoint = $dataPoints[$repeatKey] ?? null;
            if (null === $dataPoint) {
                continue;
            }
            foreach ($dataPoint as $i => $data) {
                $animalCode = $this->getFormDataValueByKey($data, $animalCodeAttributeKey);
                $animalModel = $this->getAnimalModelByOdkCode($animalCode);
                if (null === $animalModel) {
                    continue;
                }
                $newModel = clone $model;
                $newModel->heartgirth = $this->getFormDataValueByKey($data, $heartgirthAttributeKey);
                $newModel->weight_kg = $this->getFormDataValueByKey($data, $weightAttributeKey);
                $newModel->body_score = $this->getFormDataValueByKey($data, $bodyscoreAttributeKey);
                if (empty($newModel->heartgirth) && empty($newModel->weight_kg) && empty($newModel->body_score)) {
                    continue;
                }
                $newModel->animal_id = $animalModel->id;
                if (empty($newModel->event_date)) {
                    $newModel->event_date = $newModel->data_collection_date;
                }
                $newModel->latitude = $animalModel->latitude;
                $newModel->longitude = $animalModel->longitude;
                $this->saveAnimalEventModel($newModel, $k . $i, true);
            }
        }
    }

    protected function registerAnimalFeedProvided()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCowMonitoringParams();
        $groupKey = 'feed_provided';

        if (null === $rawData) {
            return;
        }

        $model = new AnimalEvent([
            'event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS,
            'data_collection_date' => $this->getDate(),
            'field_agent_id' => $this->_model->user_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        $feedGivenAttributeKey = self::getAttributeJsonKey('feed_given', $groupKey, $repeatKey);
        $feedWaterAttributeKey = self::getAttributeJsonKey('feed_water', $groupKey, $repeatKey);
        foreach ($rawData as $k => $dataPoints) {
            $dataPoint = $dataPoints[$repeatKey] ?? null;
            if (null === $dataPoint) {
                continue;
            }
            foreach ($dataPoint as $i => $data) {
                $animalCode = $this->getFormDataValueByKey($data, $animalCodeAttributeKey);
                $animalModel = $this->getAnimalModelByOdkCode($animalCode);
                if (null === $animalModel) {
                    continue;
                }
                $newModel = clone $model;
                $newModel->feed_given = $this->getFormDataValueByKey($data, $feedGivenAttributeKey);
                $newModel->animal_monitor_water = $this->getFormDataValueByKey($data, $feedWaterAttributeKey);
                if (empty($newModel->feed_given) && empty($newModel->animal_monitor_water)) {
                    continue;
                }
                if (empty($newModel->event_date)) {
                    $newModel->event_date = $newModel->data_collection_date;
                }
                $newModel->latitude = $animalModel->latitude;
                $newModel->longitude = $animalModel->longitude;
                $newModel->animal_id = $animalModel->id;
                $this->saveAnimalEventModel($newModel, $k . $i, true);
            }
        }
    }

    protected function registerAnimalHoofHealth()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCowMonitoringParams();
        $groupKey = 'hoof_health';
        $this->registerAnimalEvent($rawData, AnimalEvent::EVENT_TYPE_HOOF_HEALTH, $repeatKey, $groupKey, $animalCodeAttributeKey);
    }

    protected function registerAnimalHoofTreatment()
    {
        list($rawData, $repeat2Key, $animalCodeAttributeKey) = $this->getCowMonitoringParams();
        $groupKey = 'hoof_treatmentdetails';
        $repeat1Key = 'cow_monitoring';
        $rawData = $this->_model->form_data[$repeat1Key] ?? null;
        if (null === $rawData) {
            return;
        }
        $repeat2Key = $repeat1Key . '/cow_monitoringanimal';
        $repeat3Key = $repeat2Key . '/cow_monitoringhooftreat';
        $model = new AnimalEvent([
            'event_type' => AnimalEvent::EVENT_TYPE_HOOF_TREATMENT,
            'data_collection_date' => $this->getDate(),
            'field_agent_id' => $this->_model->user_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        foreach ($rawData as $k => $repeat1Data) {
            $repeat2DataSet = $repeat1Data[$repeat2Key] ?? null;
            if (null === $repeat2DataSet) {
                continue;
            }
            foreach ($repeat2DataSet as $i => $repeat2Data) {
                $repeat3DataSet = $repeat2Data[$repeat3Key] ?? null;
                if (null === $repeat3DataSet) {
                    continue;
                }
                foreach ($repeat3DataSet as $n => $repeat3Data) {
                    $animalCode = $this->getFormDataValueByKey($repeat3Data, $animalCodeAttributeKey);
                    $animalModel = $this->getAnimalModelByOdkCode($animalCode);
                    if (null === $animalModel) {
                        continue;
                    }
                    $newModel = clone $model;
                    if ($newModel->setDynamicAttributesValuesFromOdkForm($repeat3Data, $groupKey, $repeat3Key)) {
                        if (empty($newModel->event_date)) {
                            $newModel->event_date = $newModel->data_collection_date;
                        }
                        $newModel->animal_id = $animalModel->id;
                        $newModel->latitude = $animalModel->latitude;
                        $newModel->longitude = $animalModel->longitude;
                        $this->saveAnimalEventModel($newModel, $k . $i . $n, true);
                    }
                }
            }
        }
    }

    protected function getCowMonitoringParams()
    {
        $mainRepeatKey = 'cow_monitoring';
        $rawData = $this->_model->form_data[$mainRepeatKey] ?? null;
        $repeatKey = $mainRepeatKey . '/cow_monitoringanimal';
        $animalCodeAttributeKey = self::getAttributeJsonKey('cowmonitor_animalcode', $this->_model->isVersion1Point5() ? '' : 'cow_monitordetails', $repeatKey);
        return [$rawData, $repeatKey, $animalCodeAttributeKey];
    }

    protected function registerAnimalGrowth()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCalfMonitoringParams();
        $groupKey = 'calfmonitor_growth';
        $eventDateAttributeKey = self::getAttributeJsonKey('calfmonitor_date', $groupKey, $repeatKey);
        $this->registerAnimalEvent($rawData, AnimalEvent::EVENT_TYPE_WEIGHTS, $repeatKey, $groupKey, $animalCodeAttributeKey, $eventDateAttributeKey);
    }

    protected function registerCalfVaccination()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCalfMonitoringParams();
        $groupKey = 'calfmonitor_vaccination';
        $attributes = [
            'vacc_vaccine_date' => self::getAttributeJsonKey('calfmonitor_vaccinedate', $groupKey, $repeatKey),
            'vacc_vaccine_type' => self::getAttributeJsonKey('calfmonitor_vaccinetype', $groupKey, $repeatKey),
            'vacc_vaccine_type_other' => self::getAttributeJsonKey('calfmonitor_othervaccinetype', $groupKey, $repeatKey),
            'vacc_vaccine_provider' => self::getAttributeJsonKey('calfmonitor_vaccineprovider', $groupKey, $repeatKey),
            'vacc_vaccine_provider_other' => self::getAttributeJsonKey('calfmonitor_vaccineproviderother', $groupKey, $repeatKey),
            'vacc_vaccine_drug_cost' => self::getAttributeJsonKey('calfmonitor_vaccinedrugcost', $groupKey, $repeatKey),
            'vacc_vaccine_service_cost' => self::getAttributeJsonKey('calfmonitor_vaccineservicecost', $groupKey, $repeatKey),
            'vacc_vaccine_cow_status' => self::getAttributeJsonKey('calfmonitor_vaccinecowstatus', $groupKey, $repeatKey),
            'vacc_vaccine_cow_status_other' => self::getAttributeJsonKey('calfmonitor_parasitecowstatusother', $groupKey, $repeatKey),
        ];
        $this->registerCalfMonitoringEvents($rawData, AnimalEvent::EVENT_TYPE_VACCINATION, $repeatKey, $animalCodeAttributeKey, $attributes);
    }

    protected function registerCalfParasiteInfection()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCalfMonitoringParams();
        $groupKey = 'calfmonitor_parasiteinfection';
        $attributes = [
            'parasite_date' => self::getAttributeJsonKey('calfmonitor_parasitedate', $groupKey, $repeatKey),
            'parasite_type' => self::getAttributeJsonKey('calfmonitor_parasitetype', $groupKey, $repeatKey),
            'parasite_type_other' => self::getAttributeJsonKey('calfmonitor_parasiteother', $groupKey, $repeatKey),
            'parasite_provider' => self::getAttributeJsonKey('calfmonitor_parasiteprovider', $groupKey, $repeatKey),
            'parasite_provider_other' => self::getAttributeJsonKey('calfmonitor_parasiteproviderother', $groupKey, $repeatKey),
            'parasite_drug_cost' => self::getAttributeJsonKey('calfmonitor_parasitedrugcost', $groupKey, $repeatKey),
            'parasite_service_cost' => self::getAttributeJsonKey('calfmonitor_parasiteservicecost', $groupKey, $repeatKey),
            'parasite_cow_status' => self::getAttributeJsonKey('calfmonitor_parasitecowstatus', $groupKey, $repeatKey),
            'parasite_cow_status_other' => self::getAttributeJsonKey('calfmonitor_parasitecowstatusother', $groupKey, $repeatKey),
        ];
        $this->registerCalfMonitoringEvents($rawData, AnimalEvent::EVENT_TYPE_PARASITE_INFECTION, $repeatKey, $animalCodeAttributeKey, $attributes);
    }

    protected function registerCalfMonitoringEvents($rawData, $eventType, $repeatKey, $animalCodeAttributeKey, $attributes)
    {
        if (null === $rawData) {
            return;
        }
        $model = new AnimalEvent([
            'event_type' => $eventType,
            'data_collection_date' => $this->getDate(),
            'field_agent_id' => $this->_model->user_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);

        foreach ($rawData as $k => $dataPoints) {
            $dataPoint = $dataPoints[$repeatKey] ?? null;
            if (null === $dataPoint) {
                continue;
            }
            foreach ($dataPoint as $i => $data) {
                $animalCode = $this->getFormDataValueByKey($data, $animalCodeAttributeKey);
                $animalModel = $this->getAnimalModelByOdkCode($animalCode);
                if (null === $animalModel) {
                    continue;
                }
                $newModel = clone $model;
                $newModel->animal_id = $animalModel->id;
                foreach ($attributes as $attr => $odkKey) {
                    $newModel->{$attr} = $this->getFormDataValueByKey($data, $odkKey);
                }
                if (empty($newModel->event_date)) {
                    $newModel->event_date = $newModel->data_collection_date;
                }
                $newModel->latitude = $animalModel->latitude;
                $newModel->longitude = $animalModel->longitude;
                $this->saveAnimalEventModel($newModel, $k . $i, true);
            }
        }
    }

    protected function getCalfMonitoringParams()
    {
        $mainRepeatKey = 'calf_monitoring';
        $rawData = $this->_model->form_data[$mainRepeatKey] ?? null;
        $repeatKey = $mainRepeatKey . '/calf_monitoringanimal';
        $animalCodeAttributeKey = self::getAttributeJsonKey('calfmonitor_animalcode', $this->_model->isVersion1Point5() ? '' : 'calf_monitordetails', $repeatKey);
        return [$rawData, $repeatKey, $animalCodeAttributeKey];
    }

    /**
     * @param $rawData
     * @param $eventType
     * @param $repeatKey
     * @param $groupKey
     * @param $animalCodeAttributeKey
     * @param null $eventDateAttributeKey
     */
    protected function registerAnimalEvent($rawData, $eventType, $repeatKey, $groupKey, $animalCodeAttributeKey, $eventDateAttributeKey = null)
    {
        if (null === $rawData) {
            return;
        }


        $model = new AnimalEvent([
            'event_type' => $eventType,
            'data_collection_date' => $this->getDate(),
            'field_agent_id' => $this->_model->user_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);

        foreach ($rawData as $k => $dataPoints) {
            $dataPoint = $dataPoints[$repeatKey] ?? null;
            if (null === $dataPoint) {
                continue;
            }
            foreach ($dataPoint as $i => $data) {
                $animalCode = $this->getFormDataValueByKey($data, $animalCodeAttributeKey);
                $animalModel = $this->getAnimalModelByOdkCode($animalCode);
                if (null === $animalModel) {
                    continue;
                }
                $eventDate = $eventDateAttributeKey !== null ? $this->getFormDataValueByKey($data, $eventDateAttributeKey) : $this->getDate();
                $newModel = clone $model;
                if ($newModel->setDynamicAttributesValuesFromOdkForm($data, $groupKey, $repeatKey)) {
                    $newModel->animal_id = $animalModel->id;
                    $newModel->event_date = $eventDate;
                    if (empty($newModel->event_date)) {
                        $newModel->event_date = $newModel->data_collection_date;
                    }
                    $newModel->latitude = $animalModel->latitude;
                    $newModel->longitude = $animalModel->longitude;
                    $this->saveAnimalEventModel($newModel, $k . $i, true);
                }
            }
        }
    }

    /**
     * @param string $animalCode
     * @return  Animal|null
     */
    protected function getAnimalModelByOdkCode($animalCode)
    {
        // IF the code starts with N- e.g N-1 then its a new animal
        $isNewRecord = false;
        $animalCodeArr = explode('-', $animalCode);
        if (count($animalCodeArr) == 2) {
            $prefix = strtoupper(trim($animalCodeArr[0]));
            $number = trim($animalCodeArr[1]);
            if ($prefix === 'N' && $number >= 1) {
                $isNewRecord = true;
            }
        }
        if ($isNewRecord) {
            $model = $this->_animalsModels[$animalCode] ?? null;
        } else {
            if (OdkForm::isVersion1Point5OrBelow($this->_model->form_version)) {
                $model = Animal::find()->andWhere(['country_id' => $this->_model->country_id, 'odk_animal_code' => $animalCode])->one();
            } else {
                $model = Animal::find()->andWhere(['id' => $animalCode])->one();
            }
        }
        return $model;
    }

    /**
     * @param array $animalData
     * @param Farm $farmModel
     * @param string $index
     * @return Animal|null
     */
    protected function getOrRegisterAnimalDam($animalData, $farmModel, $index)
    {
        //todo: Pending tests
        $damCodeKey = self::getAttributeJsonKey('animal_damregistered', 'animal_damknownlist', 'animal_general');
        $animalCode = $this->getFormDataValueByKey($animalData, $damCodeKey);
        $damModel = null;
        if (!empty($animalCode)) {
            $damModel = $this->getAnimalModelByOdkCode($animalCode);
        } else {
            //register dam as a new animal
            $repeatKey = 'animal_general/animal_dam';
            $damDetailGroupKey = 'animal_damdetails';
            $dams = $animalData[$repeatKey] ?? null;
            if (empty($dams)) {
                return null;
            }
            $damData = array_values($dams)[0];
            $animalModel = new Animal([
                'farm_id' => $farmModel->id,
                'country_id' => $farmModel->country_id,
                'region_id' => $farmModel->region_id,
                'district_id' => $farmModel->district_id,
                'ward_id' => $farmModel->ward_id,
                'village_id' => $farmModel->village_id,
                'org_id' => $farmModel->org_id,
                'client_id' => $farmModel->client_id,
                'odk_form_uuid' => $this->_model->form_uuid,
                'latitude' => $farmModel->latitude,
                'longitude' => $farmModel->longitude,
                'reg_date' => $this->getDate(),
                'animal_type' => 2,//cow
            ]);

            $birthdateKey = OdkForm::isVersion1Point5OrBelow($this->_model->form_version) ? 'animal_damdobfull' : 'animal_damactualdob';
            $fixedAttributesMap = [
                'name' => self::getAttributeJsonKey('animal_damname', $damDetailGroupKey, $repeatKey),
                'tag_id' => self::getAttributeJsonKey('animal_damtagid', $damDetailGroupKey, $repeatKey),
                'main_breed' => self::getAttributeJsonKey('animal_dammainbreed', $damDetailGroupKey, $repeatKey),
                'main_breed_other' => self::getAttributeJsonKey('animal_dammainbreedoth', $damDetailGroupKey, $repeatKey),
                'breed_composition' => self::getAttributeJsonKey('animal_dammaincomp', $damDetailGroupKey, $repeatKey),
                'birthdate' => self::getAttributeJsonKey($birthdateKey, $damDetailGroupKey, $repeatKey),
            ];

            foreach ($fixedAttributesMap as $attr => $odkKey) {
                $animalModel->{$attr} = $this->getFormDataValueByKey($damData, $odkKey);
            }
            $i = 'dam_' . $index;
            $this->saveAnimalModel($animalModel, $i, true);
            $damModel = $this->_animalsModels[$i] ?? null;
        }

        return $damModel;
    }

    /**
     * @param array $animalData
     * @param Farm $farmModel
     * @param string $index
     * @return Animal|null
     */
    protected function getOrRegisterAnimalSire($animalData, $farmModel, $index)
    {
        //todo: Pending tests
        $sireType = null;
        $sireModel = null;
        $sireCodeKey = self::getAttributeJsonKey('animal_sireairegistered', 'animal_sireknownlist', 'animal_general');
        $animalCode = $this->getFormDataValueByKey($animalData, $sireCodeKey);
        if (empty($animalCode)) {
            $sireCodeKey = self::getAttributeJsonKey('animal_sirebullregistered', 'animal_sireknownlist', 'animal_general');
            $animalCode = $this->getFormDataValueByKey($animalData, $sireCodeKey);
        }
        if (!empty($animalCode)) {
            $sireModel = $this->getAnimalModelByOdkCode($animalCode);
        } else {
            //register sire as a new animal
            $repeatKey = 'animal_general/animal_sire';
            $sireBullDetailGroupKey = 'animal_siretypes/animal_sirebulldetails';
            $sireStrawDetailsGroupKey = 'animal_siretypes/animal_sirestrawdetails';
            $sires = $animalData[$repeatKey] ?? null;
            if (empty($sires)) {
                return null;
            }
            $sireData = array_values($sires)[0];
            $sireTypeAttributeKey = self::getAttributeJsonKey('animal_siretype', 'animal_siretypes', $repeatKey);
            $sireType = $this->getFormDataValueByKey($sireData, $sireTypeAttributeKey);
            $animalModel = new Animal([
                'farm_id' => $farmModel->id,
                'country_id' => $farmModel->country_id,
                'region_id' => $farmModel->region_id,
                'district_id' => $farmModel->district_id,
                'ward_id' => $farmModel->ward_id,
                'village_id' => $farmModel->village_id,
                'org_id' => $farmModel->org_id,
                'client_id' => $farmModel->client_id,
                'odk_form_uuid' => $this->_model->form_uuid,
                'latitude' => $farmModel->latitude,
                'longitude' => $farmModel->longitude,
                'reg_date' => $this->getDate(),
            ]);

            if ($sireType == 2) {
                //ai straw
                $animalModel->animal_type = 6;
                $fixedAttributesMap = [
                    'tag_id' => self::getAttributeJsonKey('animal_sirestrawid', $sireStrawDetailsGroupKey, $repeatKey),
                    'country_of_origin' => self::getAttributeJsonKey('animal_sirestrawcountry', $sireStrawDetailsGroupKey, $repeatKey),
                    'main_breed' => self::getAttributeJsonKey('animal_sirestrawbreed', $sireStrawDetailsGroupKey, $repeatKey),
                    'main_breed_other' => self::getAttributeJsonKey('animal_sirestrawbreedoth', $sireStrawDetailsGroupKey, $repeatKey),
                    'breed_composition' => self::getAttributeJsonKey('animal_sirestrawcomp', $sireStrawDetailsGroupKey, $repeatKey),
                ];
            } else {
                //bull
                $animalModel->animal_type = 5;
                $birthdateKey = OdkForm::isVersion1Point5OrBelow($this->_model->form_version) ? 'animal_siredobfull' : 'animal_sireactualdob';
                $fixedAttributesMap = [
                    'name' => self::getAttributeJsonKey('animal_sireregisteredname', $sireBullDetailGroupKey, $repeatKey),
                    'short_name' => self::getAttributeJsonKey('animal_sireshortname', $sireBullDetailGroupKey, $repeatKey),
                    'tag_id' => self::getAttributeJsonKey('animal_siretagid', $sireBullDetailGroupKey, $repeatKey),
                    'herd_book_no' => self::getAttributeJsonKey('animal_sireherdbooknumber', $sireBullDetailGroupKey, $repeatKey),
                    'country_of_origin' => self::getAttributeJsonKey('animal_sirecountry', $sireBullDetailGroupKey, $repeatKey),
                    'main_breed' => self::getAttributeJsonKey('animal_siremainbreed', $sireBullDetailGroupKey, $repeatKey),
                    'main_breed_other' => self::getAttributeJsonKey('animal_siremainbreedoth', $sireBullDetailGroupKey, $repeatKey),
                    'breed_composition' => self::getAttributeJsonKey('animal_siremaincomp', $sireBullDetailGroupKey, $repeatKey),
                    'secondary_breed' => self::getAttributeJsonKey('animal_siresecondbreed', $sireBullDetailGroupKey, $repeatKey),
                    'secondary_breed_other' => self::getAttributeJsonKey('animal_siresecondbreedoth', $sireBullDetailGroupKey, $repeatKey),
                    'birthdate' => self::getAttributeJsonKey($birthdateKey, $sireBullDetailGroupKey, $repeatKey),
                    'sire_owner' => self::getAttributeJsonKey('animal_sireowner', $sireBullDetailGroupKey, $repeatKey),
                    'sire_owner_scheme' => self::getAttributeJsonKey('animal_sireownerscheme', $sireBullDetailGroupKey, $repeatKey),
                    'sire_owner_institute' => self::getAttributeJsonKey('animal_sireownerinstitute', $sireBullDetailGroupKey, $repeatKey),
                    'sire_owner_farmer' => self::getAttributeJsonKey('animal_sireownerfarmer', $sireBullDetailGroupKey, $repeatKey),
                    'sire_owner_farmer_phone' => self::getAttributeJsonKey('animal_sireownerfarmermobile', $sireBullDetailGroupKey, $repeatKey),
                ];
            }

            foreach ($fixedAttributesMap as $attr => $odkKey) {
                $animalModel->{$attr} = $this->getFormDataValueByKey($sireData, $odkKey);
            }
            $i = 'sire_' . $index;
            $this->saveAnimalModel($animalModel, $i, true);
            $sireModel = $this->_animalsModels[$i] ?? null;
        }

        return $sireModel;
    }

    /**
     * @param Farm $model
     * @param string|int $index
     * @param bool $validate
     */
    protected function saveFarmModel($model, $index, $validate = true)
    {
        $data = $this->saveModel($model, $validate);
        $this->_farmData[$index] = $data['data'];
        /* @var $model Farm */
        $model = $data['model'];
        $this->_farmId = $model->id;
        $this->_farmModel = $model;
    }

    /**
     * @param FarmMetadata $model
     * @param $index
     * @param bool $validate
     */
    protected function saveFarmMetadataModel($model, $index, $validate = true)
    {
        $data = $this->saveModel($model, $validate);
        $this->_farmMetadata[$index] = $data['data'];
        $this->_farmMetadataModels[$index] = $data['model'];
    }

    /**
     * @param Animal $model
     * @param $index
     * @param bool $validate
     */
    protected function saveAnimalModel($model, $index, $validate = true)
    {
        $data = $this->saveModel($model, $validate);
        $this->_animalsData[$index] = $data['data'];
        $this->_animalsModels[$index] = $data['model'];
    }


    /**
     * @param AnimalEvent $model
     * @param $index
     * @param bool $validate
     */
    protected function saveAnimalEventModel($model, $index, $validate = true)
    {
        $data = $this->saveModel($model, $validate);
        $this->_animalEventsData[$index] = $data['data'];
        $this->_animalEventModels[$index] = $data['model'];
    }


    /**
     * @param ActiveRecord|TableAttributeInterface $model
     * @param bool $validate
     * @return array
     */
    protected function saveModel($model, $validate = true)
    {
        $model->ignoreAdditionalAttributes = false;
        $isSaved = $model->save($validate);
        return [
            'model' => $model,
            'data' => ['attributes' => $model->attributes, 'errors' => $isSaved ? null : $model->getErrors(),]
        ];
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