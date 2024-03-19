<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-08
 * Time: 7:42 AM
 */

namespace console\jobs;


use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\CountryUnits;
use backend\modules\core\models\Farm;
use backend\modules\core\models\FarmMetadata;
use backend\modules\core\models\FarmMetadataHouseholdMembers;
use backend\modules\core\models\FarmMetadataMilkUtilizationBuyer;
use backend\modules\core\models\FarmMetadataTechnologyMobilization;
use backend\modules\core\models\MilkingEvent;
use backend\modules\core\models\OdkForm;
use backend\modules\core\models\TableAttributeInterface;
use backend\modules\core\models\WeightEvent;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\helpers\Str;
use common\models\ActiveRecord;
use DateTime;
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
    private $_farmMetadataModels;

    private $_animalEventModels;

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

    const MIN_SUPPORTED_ODK_FORM_VERSION = OdkForm::ODK_FORM_VERSION_1_POINT_5;
    /**
     * @var array|\yii\db\ActiveRecord|null
     */

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        $this->_model = OdkForm::find()->andWhere(['id' => $this->itemId])->one();
        $form_uuid = $this->_model->form_uuid ?? null;
        Yii::$app->controller->stdout("Processing started...File Id={$form_uuid}\n");
        if ($this->_model === null) {
            Yii::$app->controller->stdout("No ODK form found with id: {$this->itemId}\n");
            return false;
        }
        try {
            if (is_string($this->_model->form_data)) {
                $this->_model->form_data = json_decode($this->_model->form_data, true);
            }
            $this->_model->has_errors = 0;
            //check the version
            if ($this->isSupportedVersion()) {
                //farmer registration
                $this->registerNewFarmer();
                //farm metadata
                $this->registerFarmerHouseholdMembers();
                $this->registerFarmerTechnologyMobilization();
                $this->registerFarmerMilkUtilization();
                $this->registerFarmerImprovedFodderAdoption();
                $this->registerConcentrate();
                $this->registerFarmerFeedbackToHousehold();
                $this->registerLandOwnership();
                $this->registerWaterSources();
                $this->registerHouseholdLivestockDetails();
                $this->registerGroupMembership();
                $this->registerCattleHousingAndStructures();
                $this->registerCattleBreedingTechnologies();
                $this->registerCattleHealthServices();
                $this->registerCattleFeedingSystems();
                $this->registerFarmExtensionServices();
                //animal registration
                $this->registerNewCattle();
                //animal events
                $this->registerAnimalSynchronization();
                $this->registerAnimalAI();
               $this->registerAnimalPD();
                $this->registerAnimalMilk();
                $this->registerAnimalCalving();
                $this->registerAnimalVaccination();
                $this->registerAnimalParasiteInfection();
                $this->registerAnimalInjury();
                #Gideon commented this. Resulting in weight record duplication
                # weight is also processed at registerAnimalWeightOnMilk()
                #$this->registerAnimalMeasureDetails();
                $this->registerAnimalHoofHealth();
                $this->registerAnimalHoofTreatment();
                $this->registerAnimalFeedProvided();
                $this->registerAnimalGrowth();
                $this->registerAnimalWeightOnMilk();
                $this->registerCalfVaccination();
                $this->registerCalfParasiteInfection();
                $this->registerCalfInjury();
                $this->registerAnimalStillBirth();
                $this->registerAnimalExit();
                $this->registerAnimalECFVaccination();
                $this->registerCattleHairSampling();


            } else {
                $message = Lang::t('This Version ({old_version}) of ODK Form is currently not supported. Version ({version}) and above are supported.', ['old_version' => $this->_model->form_version, 'version' => self::MIN_SUPPORTED_ODK_FORM_VERSION]);
                $this->_model->error_message = $message;
                $this->_model->has_errors = 1;
                Yii::$app->controller->stdout("{$message}\n");
            }

            $this->_model->is_processed = 1;
            $this->_model->processed_at = DateUtils::mysqlTimestamp();

            #Possibility of malformed UTF-8 characters in your data
            #convert to a valid UTF-8 encoding to clean up the data before further processing.
            if (!empty($this->_farmData)) {
                $this->_model->farm_data = json_decode(iconv(mb_detect_encoding(json_encode($this->_farmData), mb_detect_order(), true), 'UTF-8', json_encode($this->_farmData)), true);
            }
            if (!empty($this->_farmMetadata)) {
                $this->_model->farm_metadata = json_decode(iconv(mb_detect_encoding(json_encode($this->_farmMetadata), mb_detect_order(), true), 'UTF-8', json_encode($this->_farmMetadata)), true);
            }
            if (!empty($this->_animalsData)) {
                $this->_model->animals_data = json_decode(iconv(mb_detect_encoding(json_encode($this->_animalsData), mb_detect_order(), true), 'UTF-8', json_encode($this->_animalsData)), true);
            }
            if (!empty($this->_animalEventsData)) {
                $this->_model->animal_events_data = json_decode(iconv(mb_detect_encoding(json_encode($this->_animalEventsData), mb_detect_order(), true), 'UTF-8', json_encode($this->_animalEventsData)), true);
            }
            $this->_model->save(false);

            ODKJsonNotification::createManualNotifications(ODKJsonNotification::NOTIF_ODK_JSON, $this->_model->id);
            Yii::$app->controller->stdout("Processing successful..\n");
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $message = 'ODK FORM UUID:' . $this->_model->form_uuid . ': ' . $message;
            $trace = $e->getTraceAsString();
            Yii::$app->controller->stdout("{$message}\n");
            Yii::$app->controller->stdout("{$trace}\n");
            Yii::error($message);
            Yii::error($trace);
        }

        Yii::$app->controller->stdout("Processing ends..\n");
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
        $jsonKey = 'farmer_platformuniqueid';
        $code = $this->getFormDataValueByKey($this->_model->form_data, $jsonKey);
        $version1Point5 = OdkForm::getVersionNumber(OdkForm::ODK_FORM_VERSION_1_POINT_5);
        $formVersionNumber = OdkForm::getVersionNumber($this->_model->form_version);
        $id = null; #variable to hold farm id

        if (!empty($code) && $formVersionNumber <= $version1Point5) {
            //ver 1.5 and below:code=odk_farm_code
            $id = Farm::getScalar('id', ['odk_farm_code' => $code, 'country_id' => $this->_model->country_id]);
        } else {
            #Block to handle version 1.8 and above
            #TODO Find way to identify unique farms
            if(!empty($code)) {
                #farmer_platformuniqueid has Farm ID value
                $id = $code;
            } else {
                #farmer_platformuniqueid has no Farm ID value
                # This happens when farm registration is rejected because there is a similar farm on the DB already
                # Fetch farm ID based on country_id,org_id & farm code
                $farmersRepeatKey = 'farmer_general';
                $farmerGeneralDetailsGroupKey = 'farmer_generaldetails';
                $farmerCodeKey = self::getAttributeJsonKey('farmer_uniqueid', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);

                #Get json with farmer registration data
                $farmersRegistrationData = $this->_model->form_data[$farmersRepeatKey] ?? null;

                #check if json object had farmer registration details
                if (null !== $farmersRegistrationData) {
                    #Fetch value on index 0.Not possible to register more than one farm on the same submission
                    $farmer_code = $this->getFormDataValueByKey($farmersRegistrationData[0], $farmerCodeKey);
                    $country_code = $this->_model->country_id;
                    $farmDetails = Farm::find()->andWhere(['country_id' => $country_code, 'code'=> $farmer_code,'org_id'=>null])->one();

                    if (null !== $farmDetails) {
                        $id = $farmDetails->id;
                    }
                }
            }
        }
        $this->_farmId = $id;
    }

    /**
     * @return Farm
     */
    protected function getFarmModel()
    {
        #previously, there was a condition that was checking if the model was null
        #This was a bug. The model was not null yet if did not contain the farm details
        #Modified by Gideon 28th Feb 2024
        $this->_farmModel = Farm::find()->andWhere(['id' => $this->getFarmId()])->one();
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
    /**
     * @param string $tagIdString
     * @return mixed|string|null
     */
    protected function getTagIDFormString(string $tagIdString)
    {
        try {
            $value = null;
            #Regular expression pattern to match the tag id substring
            $pattern = '/Tag Id : (\w+\d+)<br>/';
            #Perform the regular expression match
            if ($tagIdString && preg_match($pattern, $tagIdString, $matches)) {
                $value = $matches[1] ?? null;; #Extracted substring
            }
            if (is_string($value)) {
                $value = trim($value);
            }
            return $value;
        }catch (\Exception $e) {
                $message = $e->getMessage();
                $trace = $e->getTraceAsString();
                Yii::$app->controller->stdout("{$message}\n");
                Yii::$app->controller->stdout("{$trace}\n");
            }
    }
    protected function registerNewFarmer()
    {
        //farmer registration details as defined in ODK forms
        $farmersRepeatKey = 'farmer_general';
        $farmerGeneralDetailsGroupKey = 'farmer_generaldetails';
        $farmerHouseholdHeadGroupKey = 'farmer_hhheaddetails';

        //attributes keys
        $farmTypeKey = self::getAttributeJsonKey('farmer_farmtype', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerFirstNameKey = self::getAttributeJsonKey('farmer_firstname', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerOtherNamesKey = self::getAttributeJsonKey('farmer_othnames', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerCodeKey = self::getAttributeJsonKey('farmer_uniqueid', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerPhoneKey = self::getAttributeJsonKey('farmer_mobile', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerGenderKey = self::getAttributeJsonKey('farmer_gender', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        $farmerIsHouseholdHeadKey = self::getAttributeJsonKey('farmer_hhhead', $farmerGeneralDetailsGroupKey, $farmersRepeatKey);
        //$locationStringKey = $farmersRepeatKey . '/farmer_gpslocation';
        $locationStringKey = 'farmer_general/farmer_gpslocation';
        $staffCodeKey = 'staff_code';
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
            $newFarmerModel->village_id = $this->getVillageId();
            $newFarmerModel->ward_id = $this->getWardId();
            $newFarmerModel->district_id = $this->getDistrictId();
            $newFarmerModel->region_id = $this->getRegionId();
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
            //Staff Code
            $newFarmerModel->created_by = $staffCodeKey;

            $this->saveFarmModel($newFarmerModel, $k, true);
        }

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
    protected function setFarmerHouseholdMembersNumbersAttributes(Farm $farmerModel, $index)
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

    protected function  registerNewCattle()
    {
        // Define keys for different groups of data in the form
        $repeatKey = 'animal_general';
        $animalIdentificationGroupKey = 'animal_identification';
        $animalagedetailsGroupKey = 'animal_agedetails';
        $animalbreeddetailsGroupKey = 'animal_breeddetails';
        $animalcalfdetailsGroupKey = 'animal_calfregistration';

        // Retrieve the data for the animals from the form
        $animalsData = $this->_model->form_data[$repeatKey] ?? null;

        // Get the key for the dam's code
        $damCodeKey = self::getAttributeJsonKey('animal_damplatformuniqueid', 'animal_damknownlist', 'animal_general');

        // Check if the animal data is null
        if (null === $animalsData) {
            return;
        }

        // Get the farm model
        $farmModel = $this->getFarmModel();

        // If the farm model is null, log an error and return
        if (null === $farmModel) {
            $message = 'ODK Form processor: Register New Cattle aborted. Farm cannot be null. Form UUID: ' . $this->_model->form_uuid;
            Yii::error($message);
            return;
        }
        // Create a new instance of the Animal model and set some properties
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
            'created_by' => $this->_model->user_id,
        ]);

        // Define an array of fixed attribute keys
        $fixedAttributesMap = [
            'name' => self::getAttributeJsonKey('animal_name', $animalIdentificationGroupKey, $repeatKey),
            'tag_id' => self::getAttributeJsonKey('animal_tagid', $animalIdentificationGroupKey, $repeatKey),
            'animal_type' => self::getAttributeJsonKey('animal_type', $animalagedetailsGroupKey, $repeatKey),
            'animal_photo' => self::getAttributeJsonKey('animal_photo', $animalIdentificationGroupKey, $repeatKey),
            'main_breed' => self::getAttributeJsonKey('animal_mainbreed', $animalbreeddetailsGroupKey, $repeatKey),
            'breed_composition' => self::getAttributeJsonKey('animal_maincomp', $animalbreeddetailsGroupKey, $repeatKey),
            'birthdate' => self::getAttributeJsonKey('animal_actualdob', $animalagedetailsGroupKey, $repeatKey),
        ];

        $n = 1;

        // Loop through each animal in the data
        foreach ($animalsData as $k => $animalData) {

            // Create a new instance of the Animal model
            $newAnimalModel = clone $animalModel;
            // Set the values of the attributes based on the fixed attribute keys
            foreach ($fixedAttributesMap as $attr => $odkKey) {
                // birthdate has to be processed separately
                // convert datetime to date first
                if ($attr == 'birthdate') {
                    $birthdate = new \DateTime($this->getFormDataValueByKey($animalData, $odkKey));
                    $newAnimalModel->{$attr} = $birthdate->format('Y-m-d');
                } else {
                    $newAnimalModel->{$attr} = $this->getFormDataValueByKey($animalData, $odkKey);
                }
            }

            // Set the values of any dynamic attributes based on the form data
            $newAnimalModel->setDynamicAttributesValuesFromOdkForm($animalData, $animalIdentificationGroupKey, $repeatKey);

            // Retrieve or register the dam for the animal
            $damModel = $this->getOrRegisterAnimalDam($animalData, $farmModel, $k);
            $dammodelcode = $this->getFormDataValueByKey($animalData, $damCodeKey);
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

            if ($this->_model->isVersion1Point7() ) {
                // Form version is 1.7 or lower, use $calvingRepeatKey
                $calvingRepeatKey = $repeatKey . '/animal_calfstatus';
                $calvingsData = $animalData[$calvingRepeatKey] ?? null;

                $eventModel = new CalvingEvent([
                    // 'animal_id' => $damModel->id,
                    // Assign the animal ID from the dam model code
                    'animal_id' => $dammodelcode,
                    // Set the event type to calving
                    'event_type' => CalvingEvent::EVENT_TYPE_CALVING,
                    // Assign location information from the new animals model
                    'country_id' => $newAnimalModel->country_id,
                    'region_id' => $newAnimalModel->region_id,
                    'district_id' => $newAnimalModel->district_id,
                    'ward_id' => $newAnimalModel->ward_id,
                    'village_id' => $newAnimalModel->village_id,
                    'org_id' => $newAnimalModel->org_id,
                    'client_id' => $newAnimalModel->client_id,
                    'data_collection_date' => $this->getDate(),
                    // 'event_date' => $this->getDate(),//Noted issue: No calving date in ODK form
                    'event_date' => $newAnimalModel->birthdate,
                    // Assign latitude and longitude from the new animals model
                    'latitude' => $newAnimalModel->latitude,
                    'longitude' => $newAnimalModel->longitude,
                    // Assign the field agent ID and ODK form UUID from the current model
                    'field_agent_id' => $this->_model->user_id,
                    'odk_form_uuid' => $this->_model->form_uuid,
                ]);

                // Process calving events only if calving data is present
                if (!empty($calvingsData)) {
                    foreach ($calvingsData as $i => $calvingData) {
                        $newEventModel = clone $eventModel;
                        $newEventModel->setDynamicAttributesValuesFromOdkForm($calvingData, $animalcalfdetailsGroupKey, $calvingRepeatKey);
                        $this->saveAnimalEventModel($newEventModel, $i, true);
                    }
                }

            } else {

                // Form version is 1.8 or greater, use $repeatKey
                $newEventModel = new CalvingEvent([
                    'animal_id' => $dammodelcode,
                    'event_type' => CalvingEvent::EVENT_TYPE_CALVING,
                    'country_id' => $newAnimalModel->country_id,
                    'region_id' => $newAnimalModel->region_id,
                    'district_id' => $newAnimalModel->district_id,
                    'ward_id' => $newAnimalModel->ward_id,
                    'village_id' => $newAnimalModel->village_id,
                    'org_id' => $newAnimalModel->org_id,
                    'client_id' => $newAnimalModel->client_id,
                    'data_collection_date' => $this->getDate(),
                    'event_date' => $newAnimalModel->birthdate,
                    'latitude' => $newAnimalModel->latitude,
                    'longitude' => $newAnimalModel->longitude,
                    'field_agent_id' => $this->_model->user_id,
                    'odk_form_uuid' => $this->_model->form_uuid,
                ]);


                // Check if animal_anydamselected is 1 before processing calving events
                $animalAnyDamSelected = $this->getFormDataValueByKey($animalData, 'animal_general/animal_damknownlist/animal_anydamselected');

                if ($animalAnyDamSelected == 1) {
                    $this->saveAnimalEventModel($newEventModel, $k, true);
                }
            }

        }
    }

    /**
     * Create a new animal model with the specified farm, form UUID, registration date, and user ID.
     *
     * @param  Farm  $farmModel
     * @param  string  $formUuid
     * @param  \DateTime  $regDate
     * @param  int  $userId
     * @return Animal
     */
    protected function createAnimalModel(Farm $farmModel, string $formUuid, \DateTime $regDate, int $userId): Animal
    {
        return new Animal([
            'farm_id' => $farmModel->id,
            'country_id' => $farmModel->country_id,
            'region_id' => $farmModel->region_id,
            'district_id' => $farmModel->district_id,
            'ward_id' => $farmModel->ward_id,
            'village_id' => $farmModel->village_id,
            'org_id' => $farmModel->org_id,
            'client_id' => $farmModel->client_id,
            'odk_form_uuid' => $formUuid,
            'latitude' => $farmModel->latitude,
            'longitude' => $farmModel->longitude,
            'reg_date' => $regDate,
            'created_by' => $userId,
        ]);
    }

    protected function registerFarmerTechnologyMobilization()
    {
        //todo pending tests
        $repeatKey = 'farmer_techmobilization';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $model = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadataTechnologyMobilization::TYPE_TECHNOLOGY_MOBILIZATION,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        foreach ($data as $k => $datum) {
            $newModel = clone $model;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'farmer_techmobilizationdetails', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
        }
    }

    protected function registerFarmerMilkUtilization()
    {
        $repeatKey = 'milk_utilization';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $model = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadataTechnologyMobilization::TYPE_MILK_UTILIZATION,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        $buyersMorningRepeatKey = $repeatKey . '/milk_utilizationbuyermorning';
        $buyersEveningRepeatKey = $repeatKey . '/milk_utilizationbuyerevening';
        $morningBuyerTimeAttributeKey = self::getAttributeJsonKey('milk_buyermngtime', '', $buyersMorningRepeatKey);
        $buyersMorningGroupKey = 'milk_buyermng';
        $buyersEveningGroupKey = 'milk_buyerevng';
        $eveningBuyerAttributes = [
            'milk_utilize_buyer_time' => self::getAttributeJsonKey('milk_buyerevngtime', '', $buyersEveningRepeatKey),
            'milk_utilize_buyer_type' => self::getAttributeJsonKey('milk_buyerevngtype', $buyersEveningGroupKey, $buyersEveningRepeatKey),
            'milk_utilize_buyer_type_other' => self::getAttributeJsonKey('milk_buyerevngtypeoth', $buyersEveningGroupKey, $buyersEveningRepeatKey),
            'milk_utilize_buyer_sold_quantity' => self::getAttributeJsonKey('milk_buyerevngquantity', $buyersEveningGroupKey, $buyersEveningRepeatKey),
            'milk_utilize_buyer_price' => self::getAttributeJsonKey('milk_buyerevngprice', $buyersEveningGroupKey, $buyersEveningRepeatKey),
            'milk_utilize_buyer_payment_method' => self::getAttributeJsonKey('milk_buyerevngpayment', $buyersEveningGroupKey, $buyersEveningRepeatKey),
            'milk_utilize_buyer_payment_method_other' => self::getAttributeJsonKey('milk_buyerevngpaymentoth', $buyersEveningGroupKey, $buyersEveningRepeatKey),
        ];
        foreach ($data as $k => $datum) {
            $newModel = clone $model;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'milk_utilizationyesterday', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);

            $buyerModel = new FarmMetadataMilkUtilizationBuyer([
                'farm_id' => $this->getFarmId(),
                'type' => FarmMetadataTechnologyMobilization::TYPE_MILK_UTILIZATION_BUYER,
                'country_id' => $this->_model->country_id,
                'odk_form_uuid' => $this->_model->form_uuid,
            ]);
            //save morning milk buyers
            $morningBuyers = $datum[$buyersMorningRepeatKey] ?? null;
            if (null === $morningBuyers) {
                $morningBuyers = [];
            }
            foreach ($morningBuyers as $n => $morningBuyer) {
                $newBuyerModel = clone $buyerModel;
                $newBuyerModel->milk_utilize_buyer_time = $this->getFormDataValueByKey($morningBuyer, $morningBuyerTimeAttributeKey);
                $newBuyerModel->setDynamicAttributesValuesFromOdkForm($morningBuyer, $buyersMorningGroupKey, $buyersMorningRepeatKey);
                $this->saveFarmMetadataModel($newBuyerModel, $i . 'morning_' . $n, true);
            }

            //save evening milk buyers
            $eveningBuyers = $datum[$buyersEveningRepeatKey] ?? null;
            if (null === $eveningBuyers) {
                $eveningBuyers = [];
            }
            foreach ($eveningBuyers as $n => $eveningBuyer) {
                $newBuyerModel = clone $buyerModel;
                foreach ($eveningBuyerAttributes as $attr => $odkKey) {
                    $newBuyerModel->{$attr} = $this->getFormDataValueByKey($eveningBuyer, $odkKey);
                }
                $this->saveFarmMetadataModel($newBuyerModel, $i . 'evening_' . $n, true);
            }
        }

    }

    protected function registerFarmerImprovedFodderAdoption()
    {
        $repeatKey = 'improved_fodder';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $model = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadataTechnologyMobilization::TYPE_IMPROVED_FODDER_ADOPTION,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        foreach ($data as $k => $datum) {
            $newModel = clone $model;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'improved_fodderdetails', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
        }

    }

    protected function registerConcentrate()
    {
        $repeatKey = 'feeding_systems';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $model = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadata::TYPE_CONCENTRATES,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        foreach ($data as $k => $datum) {
            $newModel = clone $model;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'concentrate_feeds', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
        }

    }

    protected function registerFarmerFeedbackToHousehold()
    {
        $this->registerFarmMetadataHasMultiple(FarmMetadata::TYPE_FEEDBACK_TO_HOUSEHOLD, 'farmer_feedback', 'farmer_feedbackmembers', 'farmer_membersdetails');
    }

    protected function registerLandOwnership()
    {
        $this->registerFarmMetadataHasMultiple(FarmMetadata::TYPE_LAND_OWNERSHIP, 'land_ownership', 'land_plots', 'land_plotsdetails');
    }

    protected function registerWaterSources()
    {
        $repeatKey = 'water_sources';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $model = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadata::TYPE_WATER_SOURCE,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        foreach ($data as $k => $datum) {
            $newModel = clone $model;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'water_home', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'water_livestock', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'water_constraints', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
        }
    }

    protected function registerHouseholdLivestockDetails()
    {
        $repeatKey = 'livestock_details';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $livestockDetailsModel = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadata::TYPE_LIVESTOCK_DETAILS,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        $otherSpeciesModel = clone $livestockDetailsModel;
        $otherSpeciesModel->type = FarmMetadata::TYPE_OTHER_SPECIES_DETAILS;
        $cattleDetailsModel = clone $livestockDetailsModel;
        $cattleDetailsModel->type = FarmMetadata::TYPE_CATTLE_DETAILS;
        $otherSpeciesRepeatKey = $repeatKey . '/livestock_other';
        $cattleDetailsRepeatKey = $repeatKey . '/livestock_cattle';
        $otherSpeciesCodeAttributeKey = self::getAttributeJsonKey('livestock_code', '', $otherSpeciesRepeatKey);
        $cattleCategoryCodeAttributeKey = self::getAttributeJsonKey('cattle_code', '', $cattleDetailsRepeatKey);
        foreach ($data as $k => $datum) {
            //livestock details
            $newModel = clone $livestockDetailsModel;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'cattle_ownership', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'cattle_dairyproblems', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
            //other species owned
            $otherSpeciesData = $datum[$otherSpeciesRepeatKey] ?? null;
            if (!empty($otherSpeciesData)) {
                foreach ($otherSpeciesData as $n => $otherSpeciesDatum) {
                    $newModel = clone $otherSpeciesModel;
                    $newModel->livestock_species = $this->getFormDataValueByKey($otherSpeciesDatum, $otherSpeciesCodeAttributeKey);
                    $newModel->setDynamicAttributesValuesFromOdkForm($otherSpeciesDatum, 'livestock_othernumber', $otherSpeciesRepeatKey);
                    $i = $newModel->type . $k . $n;
                    $this->saveFarmMetadataModel($newModel, $i, true);
                }
            }
            //cattle details
            $cattleDetailsData = $datum[$cattleDetailsRepeatKey] ?? null;
            if (!empty($cattleDetailsData)) {
                foreach ($cattleDetailsData as $n => $cattleDetailsDatum) {
                    $newModel = clone $cattleDetailsModel;
                    $newModel->cattle_category = $this->getFormDataValueByKey($cattleDetailsDatum, $cattleCategoryCodeAttributeKey);
                    $newModel->setDynamicAttributesValuesFromOdkForm($cattleDetailsDatum, 'cattle_number', $cattleDetailsRepeatKey);
                    $i = $newModel->type . $k . $n;
                    $this->saveFarmMetadataModel($newModel, $i, true);
                }
            }
        }
    }

    protected function registerGroupMembership()
    {
        $this->registerFarmMetadataHasMultiple(FarmMetadata::TYPE_GROUP_MEMBERSHIP, 'livestock_group', 'livestock_groupmember', 'group_membership');
    }

    protected function registerCattleHousingAndStructures()
    {
        $repeatKey = 'cattle_housing';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $structureDetailsRepeatKey = $repeatKey . '/cattle_housingstructures';
        $housingModel = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadata::TYPE_CATTLE_HOUSING_AND_STRUCTURES,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        $structureDetailsModel = clone $housingModel;
        $structureDetailsModel->type = FarmMetadata::TYPE_FARM_STRUCTURE_DETAILS;
        foreach ($data as $k => $datum) {
            //Cattle Housing and Structures
            $newModel = clone $housingModel;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'cattle_housed/cattle_house', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'cattle_housed', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
            //Farm structures details
            $farmStructuresData = $datum[$structureDetailsRepeatKey] ?? null;
            if (!empty($farmStructuresData)) {
                foreach ($farmStructuresData as $n => $farmStructureData) {
                    $newModel = clone $structureDetailsModel;
                    $newModel->setDynamicAttributesValuesFromOdkForm($farmStructureData, '', $structureDetailsRepeatKey);
                    $newModel->setDynamicAttributesValuesFromOdkForm($farmStructureData, 'house_cattlestructures', $structureDetailsRepeatKey);
                    $i = $newModel->type . $k . $n;
                    $this->saveFarmMetadataModel($newModel, $i, true);
                }
            }
        }
    }

    protected function registerCattleBreedingTechnologies()
    {
        $repeatKey = 'breeding_tech';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $breedingTechModel = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadata::TYPE_BREEDING_TECHNOLOGIES,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        $ownBullsRepeatKey = $repeatKey . '/breed_owndetails';
        $otherBullsRepeatKey = $repeatKey . '/breed_otherdetails';
        $schemeBullsRepeatKey = $repeatKey . '/breed_schemedetails';
        $AIProviderRepeatKey = $repeatKey . '/breed_aidetails';
        $breedingTechOwnBullsModel = clone $breedingTechModel;
        $breedingTechOwnBullsModel->type = FarmMetadata::TYPE_BREEDING_BULLS;
        $breedingTechOtherBullsModel = clone $breedingTechModel;
        $breedingTechOtherBullsModel->type = FarmMetadata::TYPE_BREEDING_OTHER_BULLS;
        $breedingTechSchemeBullsModel = clone $breedingTechModel;
        $breedingTechSchemeBullsModel->type = FarmMetadata::TYPE_BREEDING_SCHEME_BULLS;
        $AIProviderModel = clone $breedingTechModel;
        $AIProviderModel->type = FarmMetadata::TYPE_BREEDING_AI_PROVIDERS;

        foreach ($data as $k => $datum) {
            $newModel = clone $breedingTechModel;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'breed_methods', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'breed_own', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'breed_other', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'breed_scheme', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'breed_ai', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
            //Details of Own Bull used
            $ownBullsData = $datum[$ownBullsRepeatKey] ?? null;
            if (!empty($ownBullsData)) {
                foreach ($ownBullsData as $n => $ownBullData) {
                    $newModel = clone $breedingTechOwnBullsModel;
                    $newModel->setDynamicAttributesValuesFromOdkForm($ownBullData, 'breed_owndetail', $ownBullsRepeatKey);
                    $i = $newModel->type . $k . $n;
                    $this->saveFarmMetadataModel($newModel, $i, true);
                }
            }
            //Details of Other Farmer bull used
            $otherBullsData = $datum[$otherBullsRepeatKey] ?? null;
            if (!empty($otherBullsData)) {
                foreach ($otherBullsData as $n => $otherBullData) {
                    $newModel = clone $breedingTechOtherBullsModel;
                    $newModel->setDynamicAttributesValuesFromOdkForm($otherBullData, 'breed_otherdetail', $otherBullsRepeatKey);
                    $i = $newModel->type . $k . $n;
                    $this->saveFarmMetadataModel($newModel, $i, true);
                }
            }
            //Details of Scheme Bull used
            $schemeBullsData = $datum[$schemeBullsRepeatKey] ?? null;
            if (!empty($schemeBullsData)) {
                foreach ($schemeBullsData as $n => $schemeBullData) {
                    $newModel = clone $breedingTechSchemeBullsModel;
                    $newModel->setDynamicAttributesValuesFromOdkForm($schemeBullData, 'breed_schemedetail', $schemeBullsRepeatKey);
                    $i = $newModel->type . $k . $n;
                    $this->saveFarmMetadataModel($newModel, $i, true);
                }
            }
            //Details of AI provider
            $AIProvidersData = $datum[$AIProviderRepeatKey] ?? null;
            if (!empty($AIProvidersData)) {
                foreach ($AIProvidersData as $n => $AIProviderData) {
                    $newModel = clone $AIProviderModel;
                    $newModel->setDynamicAttributesValuesFromOdkForm($AIProviderData, 'breed_aidetail', $AIProviderRepeatKey);
                    $newModel->setDynamicAttributesValuesFromOdkForm($AIProviderData, 'breed_aidetail/breed_aidetailtype', $AIProviderRepeatKey);
                    $i = $newModel->type . $k . $n;
                    $this->saveFarmMetadataModel($newModel, $i, true);
                }
            }
        }
    }

    protected function registerCattleHealthServices()
    {
        $repeatKey = 'health_services';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $model = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadata::TYPE_HEALTH_SERVICES,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        foreach ($data as $k => $datum) {
            $newModel = clone $model;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'health_anthdetails', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'health_tickdetails', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'health_vaccdetails', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'health_prevdetails', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'health_othdetails', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
        }
    }

    protected function registerCattleFeedingSystems()
    {
        $repeatKey = 'feeding_systems';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $model = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadata::TYPE_FEEDING_SYSTEMS,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        foreach ($data as $k => $datum) {
            $newModel = clone $model;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'feeding_cattle', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'fodder_grown', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'fodder_purchases', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'residue_feeds', $repeatKey);
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'concentrate_feeds', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
        }
    }

    protected function registerFarmExtensionServices()
    {


        $repeatKey = 'farm_extension';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $model = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => FarmMetadata::TYPE_FARM_EXTENSION_SERVICES,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        foreach ($data as $k => $datum) {
            $newModel = clone $model;
            $newModel->setDynamicAttributesValuesFromOdkForm($datum, 'farm_extensionservices', $repeatKey);
            $i = $newModel->type . $k;
            $this->saveFarmMetadataModel($newModel, $i, true);
        }
    }

    /**
     * @param int $metadataType
     * @param string $firstRepeatKey
     * @param string $secondRepeatKey
     * @param string|null $groupKey
     */
    protected function registerFarmMetadataHasMultiple($metadataType, $firstRepeatKey, $secondRepeatKey, $groupKey = null)
    {
        $data = $this->_model->form_data[$firstRepeatKey] ?? null;
        if (empty($data)) {
            return;
        }
        $model = new FarmMetadata([
            'farm_id' => $this->getFarmId(),
            'type' => $metadataType,
            'country_id' => $this->_model->country_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);
        $secondRepeatKey = $firstRepeatKey . '/' . $secondRepeatKey;
        foreach ($data as $k => $datum) {
            $dataLines = $datum[$secondRepeatKey] ?? null;
            if (null == $dataLines) {
                continue;
            }
            foreach ($dataLines as $i => $dataLine) {
                $newModel = clone $model;
                $newModel->setDynamicAttributesValuesFromOdkForm($dataLine, $groupKey, $secondRepeatKey);
                $i = $newModel->type . $i . $k;
                $this->saveFarmMetadataModel($newModel, $i, true);
            }
        }
    }

    protected function registerAnimalSynchronization()
    {
        //todo pending tests
        $repeatKey = 'animal_breeding';
        $data = $this->_model->form_data[$repeatKey] ?? null;
        $syncRepeatKey = $repeatKey . '/animal_breedingsync';
        $syncGroupKey = 'breeding_syncdetails';
        $animalCodeAttributeKey = self::getAttributeJsonKey('breeding_syncanimalplatformuniqueid', '', $syncRepeatKey);
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
        $animalCodeAttributeKey = self::getAttributeJsonKey('breeding_aianimalplatformuniqueid', '', $aiRepeatKey);
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
        $animalCodeAttributeKey = self::getAttributeJsonKey('breeding_pdanimalplatformuniqueid', '', $pdRepeatKey);
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

    # Gideon 2024-02-18
    protected function  registerCalfWeightAfterCalving($data,$repeatKey,$groupKey,$params){
        $weight_measure_details = "cow_monitoring/cow_monitoringanimal/weight_measure_details/";
        $calving_details = "cow_monitoring/cow_monitoringanimal/calving_details/";

        #Define the old and new key names
        $keyMappings = array(
            $weight_measure_details."measure_bodyscore" =>  $calving_details."calfmonitor_bodyscore",
            $weight_measure_details."measure_heartgirth" => $calving_details."calfmonitor_heartgirth",
            $weight_measure_details."measure_weight" => $calving_details."calfmonitor_weight"
        );

        #Loop through each key in the mapping -> Need to rename some keys so that they can be referenced with setting additional attributes
        foreach ($keyMappings as $oldKey => $newKey) {
            // Check if the old key exists
            if (isset($data[$oldKey])) {
                // Create a new element with the new key and assign the value
                $data[$newKey] = $data[$oldKey];
                // Remove the old key
                unset($data[$oldKey]);
            }
        }


        $calf_id = $params['calf_id'];
        $event_date = $params['event_date'] !== null ? $params['event_date'] : $this->getDate();

        #remove param values not required by weight event
        unset($params["calf_id"]);
        unset($params["event_date"]);

        $animalModel = $this->getAnimalModelByOdkCode($calf_id,null,null,null); #get calf data for reuse i.e lon,lat, country_id & admin units


        // If the animal model is null, log an error and return
        if (null === $animalModel) {
            $message = 'ODK Form processor: Register New Calf Weight aborted. Calf Details Not Found. Form UUID: ' . $this->_model->form_uuid;
            Yii::error($message);
            return;
        } else {
            $weightModel = new WeightEvent($params);
            $weightModel->animal_id = $calf_id ?? null;
            $weightModel->event_date = $event_date;
            $weightModel->setDynamicAttributesValuesFromOdkForm($data, $groupKey, $repeatKey);
            $this->saveAnimalEventModel($weightModel, 0, true);
        }

    }

    # Gideon 2024-02-18
    protected function  registerCalfAfterCalving($data,$repeatKey,$groupKey,$damId,$params){
        $calf_identification_groupKey = "calf_identification";
        $calf_breed_groupKey = "calf_breeddetails";
        $calf_sire_groupKey = "calf_sireknownlist";

        #global params
        $uuid = $params['odk_form_uuid'];
        $user_id = $params['field_agent_id'];
        $longitude = $params['longitude'];
        $latitude = $params['latitude'];

        # Get Dam Details
        $damAnimalModel = $this->getAnimalModelByOdkCode($damId,null,null);

        # Get calf details
        $calving_date = $this->getFormDataValueByKey($data, self::getAttributeJsonKey('calving_date', $groupKey, $repeatKey));
        $calf_sex = $this->getFormDataValueByKey($data, self::getAttributeJsonKey('calfsex', $groupKey, $repeatKey));
        $calf_animal_type = ($calf_sex === "1") ? 3 : 4; # set animal type. 3 is male calf; 4 is female calf
        $calf_name = $this->getFormDataValueByKey($data, self::getAttributeJsonKey('calf_name', $calf_identification_groupKey, $repeatKey));
        $calf_tagid = $this->getFormDataValueByKey($data, self::getAttributeJsonKey('calf_tagid', $calf_identification_groupKey, $repeatKey));
        $calf_main_breed_comp = $this->getFormDataValueByKey($data, self::getAttributeJsonKey('calf_maincomp', $calf_breed_groupKey, $repeatKey));
        $calf_mainbreed = $this->getFormDataValueByKey($data, self::getAttributeJsonKey('calf_mainbreed', $calf_breed_groupKey, $repeatKey));

        #sire details
        $sire_tag_id = null;
        $calf_sire_type = $this->getFormDataValueByKey($data, self::getAttributeJsonKey('calf_siretype', $calf_sire_groupKey, $repeatKey));
        if($calf_sire_type ==="1"){ # bull
            $calf_sire_id = $this->getFormDataValueByKey($data, self::getAttributeJsonKey('calf_sirebullplatformuniqueid', $calf_sire_groupKey, $repeatKey));
            $sireModel = $this->getAnimalModelByOdkCode($calf_sire_id,null,null);
            if(null!==$sireModel){
                $sire_tag_id =$sireModel->tag_id;
            }
        } else { # ai
            $calf_sire_id = $this->getFormDataValueByKey($data, self::getAttributeJsonKey('calf_sireairegistered', $calf_sire_groupKey, $repeatKey));
            $sireModel = $this->getAnimalModelByOdkCode($calf_sire_id,null,null);
            if(null!==$sireModel){
                $sire_tag_id =$sireModel->tag_id;
            }
        }
        #Need to rename some keys so that they can be referenced with setting additional attributes
        #Define the old and new key names

        $calving_details_key = "cow_monitoring/cow_monitoringanimal/calving_details/";
        $calf_identification_key = "cow_monitoring/cow_monitoringanimal/calf_identification/";
        $keyMappings = array(
            $calving_details_key."calfregistration_deformities" => $calving_details_key."animal_deformities",
            $calf_identification_key."calf_color" =>  $calving_details_key."animal_color",
            #below keys are for backward compatility
            $calving_details_key."calvtype" =>  $calving_details_key."calfregistration_calvingtype",
            $calving_details_key."easecalv" =>  $calving_details_key."calfregistration_calvingease",
            $calving_details_key."calfdeformities" =>  $calving_details_key."calfregistration_deformities",
            $calving_details_key."birthtyp" =>  $calving_details_key."calfregistration_birthtype",
            $calving_details_key."calving_type_other" =>  $calving_details_key."calfregistration_calvingtypeother",
            $calving_details_key."calving_ease_other" =>  $calving_details_key."calfregistration_calvingeaseother",
            $calving_details_key."calfdeformitiesoth" =>  $calving_details_key."calfregistration_deformitiesother",
            $calving_details_key."intuse" =>  $calving_details_key."calfregistration_intendeduse",
            $calving_details_key."intuseoth" =>  $calving_details_key."calfregistration_intendeduseother"
        );

        #Loop through each key in the mapping
        foreach ($keyMappings as $oldKey => $newKey) {
            // Check if the old key exists
            if (isset($data[$oldKey])) {
                // Create a new element with the new key and assign the value
                $data[$newKey] = $data[$oldKey];
                // Remove the old key
                unset($data[$oldKey]);
            }
        }

        # Animal(Calf) Modelling
        $calfModel = new Animal([
            'name' => $calf_name,
            'tag_id' =>  $calf_tagid,
            'animal_type' => $calf_animal_type,
            'main_breed' => $calf_mainbreed,
            'breed_composition' => $calf_main_breed_comp,
            'birthdate' =>  $calving_date,
            'farm_id' => $damAnimalModel->farm_id,
            'dam_id' => $damId,
            'dam_tag_id' => $damAnimalModel->tag_id,
            'sire_type' => $calf_sire_type,
            'sire_id' => $calf_sire_id,
            'sire_tag_id' => $sire_tag_id,
            'country_id' => $damAnimalModel->country_id,
            'region_id' => $damAnimalModel->region_id,
            'district_id' => $damAnimalModel->district_id,
            'ward_id' => $damAnimalModel->ward_id,
            'village_id' => $damAnimalModel->village_id,
            'org_id' => $damAnimalModel->org_id,
            'client_id' => $damAnimalModel->client_id,
            'odk_form_uuid' => $uuid,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'reg_date' => $this->getDate(),
            'created_by' => $user_id
        ]);



        // Set the values of any dynamic attributes based on the form data
        $calfModel->setDynamicAttributesValuesFromOdkForm($data, $groupKey, $repeatKey);

        $i = 'N-1';
        $this->saveAnimalModel($calfModel, $i, true);

        #get details of already registered animal
        $newAnimalModel = $this->_animalsModels[$i];
        $calf_id = $newAnimalModel->id;

        # calf weight
        if (null !== $calf_id) {
            $params['event_type'] = 6; # reset event type to weight from calving
            $params['calf_id'] =$calf_id ;
            $params['event_date'] = $calving_date;
            # call function to register calf weight
            $this->registerCalfWeightAfterCalving($data,$repeatKey,$groupKey,$params);
        }

    }

    protected function registerAnimalCalving()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCowMonitoringParams();
        $groupKey = 'calving_details';
        $eventDateAttributeKey = self::getAttributeJsonKey('calving_date', $groupKey, $repeatKey);
        $this->registerAnimalEvent($rawData, AnimalEvent::EVENT_TYPE_CALVING, $repeatKey, $groupKey, $animalCodeAttributeKey, $eventDateAttributeKey);
    }

    protected function registerAnimalWeightOnMilk()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCowMonitoringParams();
        $groupKey = 'measure_details';
        $eventDateAttributeKey = self::getAttributeJsonKey('milk_milkdate', 'milk_prodanimal', $repeatKey);
        $this->registerAnimalEvent($rawData, AnimalEvent::EVENT_TYPE_WEIGHTS, $repeatKey, $groupKey, $animalCodeAttributeKey, $eventDateAttributeKey);
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
        $newAnimalCodeAttributeKey = Str::str_lreplace('platformuniqueid', 'code', $animalCodeAttributeKey);

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
                if (Str::isEmpty($animalCode)) {
                    $animalCode = $this->getFormDataValueByKey($data, $newAnimalCodeAttributeKey);
                }
                $animalModel = $this->getAnimalModelByOdkCode($animalCode,null,null);
                $newModel = clone $model;
                $newModel->heartgirth = $this->getFormDataValueByKey($data, $heartgirthAttributeKey);
                $newModel->weight_kg = $this->getFormDataValueByKey($data, $weightAttributeKey);
                $newModel->body_score = $this->getFormDataValueByKey($data, $bodyscoreAttributeKey);
                if (empty($newModel->heartgirth) && empty($newModel->weight_kg) && empty($newModel->body_score)) {
                    continue;
                }
                $newModel->animal_id = $animalModel->id ?? null;
                if (empty($newModel->event_date)) {
                    $newModel->event_date = $newModel->data_collection_date;
                }
                $newModel->latitude = $animalModel->latitude ?? null;
                $newModel->longitude = $animalModel->longitude ?? null;
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
            'event_type' => AnimalEvent::EVENT_TYPE_FEEDING,
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
                $animalCode =null;
                $animalCode = $this->getFormDataValueByKey($data, $animalCodeAttributeKey);
                #Code Block added by Gideon 28th Feb 2024
                #If new animal reg, get animal identification from code attribute key rather than platformuniqueid attribute key on the Submitted JSON
                if (Str::isEmpty($animalCode)) {
                    $newAnimalCodeAttributeKey = Str::str_lreplace('platformuniqueid', 'code', $animalCodeAttributeKey);
                    $animalCode = $this->getFormDataValueByKey($data, $newAnimalCodeAttributeKey);
                }

                #need to extract animal id
                $eventType = AnimalEvent::EVENT_TYPE_FEEDING;
                $fakeEventType = $this->getFakeEventType($eventType,$repeatKey);
                $animalModel = $this->getAnimalModelByOdkCode($animalCode,$data,$fakeEventType);

                $newModel = clone $model;
                $newModel->feed_given = $this->getFormDataValueByKey($data, $feedGivenAttributeKey);
                $newModel->animal_monitor_water = $this->getFormDataValueByKey($data, $feedWaterAttributeKey);
                if (empty($newModel->feed_given) && empty($newModel->animal_monitor_water)) {
                    continue;
                }
                if (empty($newModel->event_date)) {
                    $newModel->event_date = $newModel->data_collection_date;
                }
                $newModel->latitude = $animalModel->latitude ?? null;
                $newModel->longitude = $animalModel->longitude ?? null;
                $newModel->animal_id = $animalModel->id ?? null;
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
                    $animalModel = $this->getAnimalModelByOdkCode($animalCode,null,null);
                    $newModel = clone $model;
                    if ($newModel->setDynamicAttributesValuesFromOdkForm($repeat3Data, $groupKey, $repeat3Key)) {
                        if (empty($newModel->event_date)) {
                            $newModel->event_date = $newModel->data_collection_date;
                        }
                        $newModel->animal_id = $animalModel->id ?? null;
                        $newModel->latitude = $animalModel->latitude ?? null;
                        $newModel->longitude = $animalModel->longitude ?? null;
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
        $animalCodeAttributeKey = self::getAttributeJsonKey('cowmonitor_animalplatformuniqueid', $this->_model->isVersion1Point5() ? '' : '', $repeatKey);
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

    protected function registerCalfInjury()
    {
        list($rawData, $repeatKey, $animalCodeAttributeKey) = $this->getCalfMonitoringParams();
        $groupKey = 'calfmonitor_injury';
        $attributes = [
            'injury_date' => self::getAttributeJsonKey('calfmonitor_injurydate', $groupKey, $repeatKey),
            'injury_type' => self::getAttributeJsonKey('calfmonitor_injurytype', $groupKey, $repeatKey),
            'injury_type_other' => self::getAttributeJsonKey('calfmonitor_injurytypeother', $groupKey, $repeatKey),
            'injury_service_provider' => self::getAttributeJsonKey('calfmonitor_injuryserviceprovider', $groupKey, $repeatKey),
            'injury_service_provider_other' => self::getAttributeJsonKey('calfmonitor_injuryserviceproviderother', $groupKey, $repeatKey),
            'injury_drug_cost' => self::getAttributeJsonKey('calfmonitor_injurydrugcost', $groupKey, $repeatKey),
            'injury_service_cost' => self::getAttributeJsonKey('calfmonitor_injuryservicecost', $groupKey, $repeatKey),
            'injury_cow_status' => self::getAttributeJsonKey('calfmonitor_injurycowstatus', $groupKey, $repeatKey),
            'injury_cow_status_other' => self::getAttributeJsonKey('calfmonitor_injurycowstatusother', $groupKey, $repeatKey),
        ];
        $this->registerCalfMonitoringEvents($rawData, AnimalEvent::EVENT_TYPE_INJURY, $repeatKey, $animalCodeAttributeKey, $attributes);
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
                $animalModel = $this->getAnimalModelByOdkCode($animalCode,null,null);
                $newModel = clone $model;
                $newModel->animal_id = $animalModel->id ?? null;
                foreach ($attributes as $attr => $odkKey) {
                    $newModel->{$attr} = $this->getFormDataValueByKey($data, $odkKey);
                }
                if (empty($newModel->event_date)) {
                    $newModel->event_date = $newModel->data_collection_date;
                }
                $newModel->latitude = $animalModel->latitude ?? null;
                $newModel->longitude = $animalModel->longitude ?? null;
                $this->saveAnimalEventModel($newModel, $k . $i, true);
            }
        }
    }

    protected function getCalfMonitoringParams()
    {
        $mainRepeatKey = 'calf_monitoring';
        $rawData = $this->_model->form_data[$mainRepeatKey] ?? null;
        $repeatKey = $mainRepeatKey . '/calf_monitoringanimal';
        $animalCodeAttributeKey = self::getAttributeJsonKey(
            'calfmonitor_animalplatformuniqueid',
            '',
            $mainRepeatKey . '/calf_monitoringanimal'
        );

        return [$rawData, $repeatKey, $animalCodeAttributeKey];
    }

    protected function registerAnimalStillBirth()
    {
        $mainRepeatKey = 'cow_stillbirth';
        $rawData = $this->_model->form_data[$mainRepeatKey] ?? null;
        $repeatKey = $mainRepeatKey . '/cow_stillbirthanimal';
        $animalCodeAttributeKey = self::getAttributeJsonKey('cowstillbirth_animalplatformuniqueid', '', $repeatKey);
        $groupKey = 'cow_stillbirthanimaldetails';
        $sireGroupKey = 'cow_stillbirthsire';
        $attributes = [
            'still_birth_calving_type' => self::getAttributeJsonKey('cowstillbirth_calvingtype', $groupKey, $repeatKey),
            'birthdate' => self::getAttributeJsonKey('cowstillbirth_date', $groupKey, $repeatKey),
            'whydead' => self::getAttributeJsonKey('cowstillbirth_deathreason', $groupKey, $repeatKey),
            'whydeadoth' => self::getAttributeJsonKey('cowstillbirth_deathreasonother', $groupKey, $repeatKey),
            'calfsex' => self::getAttributeJsonKey('cowstillbirth_sex', $groupKey, $repeatKey),
            'bull_id' => self::getAttributeJsonKey('cow_stillbirthsiretag', $sireGroupKey, $repeatKey),
        ];
        $this->registerCalfMonitoringEvents($rawData, AnimalEvent::EVENT_TYPE_CALVING, $repeatKey, $animalCodeAttributeKey, $attributes);
    }

    protected function registerAnimalExit()
    {
        $mainRepeatKey = 'cattle_exit';
        $rawData = $this->_model->form_data[$mainRepeatKey] ?? null;
        if (null === $rawData) {
            return;
        }
        $repeatKey = $mainRepeatKey . '/cattle_exitanimal';
        $animalCodeAttributeKey = self::getAttributeJsonKey('animalexit_platformuniqueid', '', $repeatKey);
        $exitDetailsGroupKey = 'animalexit_details';
        $exitMovementGroupKey = 'animalexit_movement';
        $exitNewFarmGroupKey = 'animalexit_newonwer_farmer';
        $eventDateAttributeKey = self::getAttributeJsonKey('animalexit_date', $exitDetailsGroupKey, $repeatKey);
        $newOwnerTypeAttributeKey = self::getAttributeJsonKey('animalexit_newownertype', '', $repeatKey);

        $model = new AnimalEvent([
            'event_type' => AnimalEvent::EVENT_TYPE_EXITS,
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
                $animalModel = $this->getAnimalModelByOdkCode($animalCode,null,null);
                $eventDate = $this->getFormDataValueByKey($data, $eventDateAttributeKey);
                $newModel = clone $model;
                $newModel->animal_id = $animalModel->id ?? null;
                $newModel->setDynamicAttributesValuesFromOdkForm($data, $exitDetailsGroupKey, $repeatKey);
                $newModel->setDynamicAttributesValuesFromOdkForm($data, $exitMovementGroupKey, $repeatKey);
                $newModel->setDynamicAttributesValuesFromOdkForm($data, $exitNewFarmGroupKey, $repeatKey);
                $newModel->exit_new_owner_type = $this->getFormDataValueByKey($data, $newOwnerTypeAttributeKey);
                $newModel->event_date = $eventDate;
                if (empty($newModel->event_date)) {
                    $newModel->event_date = $newModel->data_collection_date;
                }
                $newModel->latitude = $animalModel->latitude ?? null;
                $newModel->longitude = $animalModel->longitude ?? null;
                $this->saveAnimalEventModel($newModel, $k . $i, true);
            }
        }
    }

    protected function registerAnimalECFVaccination()
    {
        $mainRepeatKey = 'ecf_vaccination';
        $rawData = $this->_model->form_data[$mainRepeatKey] ?? null;
        if (null === $rawData) {
            return;
        }
        $model = new AnimalEvent([
            'event_type' => AnimalEvent::EVENT_TYPE_VACCINATION,
            'data_collection_date' => $this->getDate(),
            'field_agent_id' => $this->_model->user_id,
            'odk_form_uuid' => $this->_model->form_uuid,
        ]);

        $animalRepeat = $mainRepeatKey . '/ecf_vaccinationanimal';
        $animalCodeAttributeKey = self::getAttributeJsonKey('ecf_vaccinationanimalcode', '', $animalRepeat);
        foreach ($rawData as $k => $dataPoints) {
            $dataPoint = $dataPoints[$animalRepeat] ?? null;
            if (null === $dataPoint) {
                continue;
            }
            $baseModel = clone $model;
            $baseModel->setDynamicAttributesValuesFromOdkForm($dataPoints, 'ecf_vaccinationvial', $mainRepeatKey);
            $baseModel->setDynamicAttributesValuesFromOdkForm($dataPoints, 'ecf_vaccinationlist', $mainRepeatKey);
            foreach ($dataPoint as $i => $data) {
                $animalCode = $this->getFormDataValueByKey($data, $animalCodeAttributeKey);
                $animalModel = $this->getAnimalModelByOdkCode($animalCode,$data,AnimalEvent::EVENT_TYPE_VACCINATION);
                $newModel = clone $baseModel;
                $newModel->animal_id = $animalModel->id ?? null;
                $newModel->setDynamicAttributesValuesFromOdkForm($data, 'ecf_vaccinationdetails', $animalRepeat);
                $newModel->event_date = $newModel->ecf_date;
                if (empty($newModel->event_date)) {
                    $newModel->event_date = $newModel->data_collection_date;
                }
                $newModel->latitude = $animalModel->latitude ?? null;
                $newModel->longitude = $animalModel->longitude ?? null;
                $this->saveAnimalEventModel($newModel, $k . $i, true);
            }
        }
    }

    protected function registerCattleHairSampling()
    {
        $mainRepeatKey = 'animal_hairsampling';
        $rawData = $this->_model->form_data[$mainRepeatKey] ?? null;
        $repeatKey = $mainRepeatKey . '/animal_hairsamplingdetails';
        $animalCodeAttributeKey = self::getAttributeJsonKey('hairsampling_animalplatformuniqueid', '', $repeatKey);
        $groupKey = 'hairsampling_details';
        $this->registerAnimalEvent($rawData, AnimalEvent::EVENT_TYPE_SAMPLING, $repeatKey, $groupKey, $animalCodeAttributeKey);
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

        $newAnimalCodeAttributeKey = Str::str_lreplace('platformuniqueid', 'code', $animalCodeAttributeKey);
        $calfRegistrationAttributeKey = self::getAttributeJsonKey('calf_registration', $groupKey, $repeatKey);
        $geolocation = $this->_model->geolocation;

        $params = [
            'event_type' => $eventType,
            'data_collection_date' => $this->getDate(),
            'field_agent_id' => $this->_model->user_id,
            'created_by' => $this->_model->user_id,
            'odk_form_uuid' => $this->_model->form_uuid,
            'longitude' =>   $geolocation[1],
            'latitude' =>  $geolocation[0]
        ];
        switch ($eventType) {
            case AnimalEvent::EVENT_TYPE_CALVING:
                $model = new CalvingEvent($params);
                break;
            case AnimalEvent::EVENT_TYPE_MILKING:
                $model = new MilkingEvent($params);
                break;
            case AnimalEvent::EVENT_TYPE_WEIGHTS:
                $model = new WeightEvent($params);
                break;
            default:
                $model = new AnimalEvent($params);
        }

        foreach ($rawData as $k => $dataPoints) {
            $dataPoint = $dataPoints[$repeatKey] ?? null;


            if (null === $dataPoint) {
                continue;
            }
            foreach ($dataPoint as $i => $data) {
                $animalCode = null;
                $animalCode = $this->getFormDataValueByKey($data, $animalCodeAttributeKey);

                if (Str::isEmpty($animalCode)) {
                    $animalCode = $this->getFormDataValueByKey($data, $newAnimalCodeAttributeKey);
                }
                $eventDate = $eventDateAttributeKey !== null ? $this->getFormDataValueByKey($data, $eventDateAttributeKey) : $this->getDate();


                $fakeEventType = $this->getFakeEventType($eventType,$repeatKey);
                $animalModel = $this->getAnimalModelByOdkCode($animalCode,$data,$fakeEventType);

                #fake event 60 is a fake weight event
                # It is used to  process weight data received when milking
                # There is need to separate this from the normal weight processing
                if($fakeEventType ===60){
                    $dataPointMilk = $dataPoints[$repeatKey] ?? null;
                    $eventDate = $eventDateAttributeKey !== null ? $this->getFormDataValueByKey($dataPointMilk[$i], $eventDateAttributeKey) : $this->getDate();

                    # The keys received for milk is not what is expected. Keys are defined on core_table_attribute table
                    #Define the old and new key names
                    $weight_measure_details = $repeatKey.'/'.$groupKey.'/';
                    $keyMappings = array(
                        $weight_measure_details."measure_bodyscore" =>  $weight_measure_details."calfmonitor_bodyscore",
                        $weight_measure_details."measure_heartgirth" => $weight_measure_details."calfmonitor_heartgirth",
                        $weight_measure_details."measure_weight" => $weight_measure_details."calfmonitor_weight"
                    );
                    #Loop through each key in the mapping -> Need to rename some keys so that they can be referenced with setting additional attributes
                    foreach ($keyMappings as $oldKey => $newKey) {
                        // Check if the old key exists
                        if (isset($data[$oldKey])) {
                            // Create a new element with the new key and assign the value
                            $data[$newKey] = $data[$oldKey];
                            // Remove the old key
                            unset($data[$oldKey]);
                        }
                    }
                }

                $newModel = clone $model;

                if ($newModel->setDynamicAttributesValuesFromOdkForm($data, $groupKey, $repeatKey)) {
                    $newModel->animal_id = $animalModel->id ?? null;
                    $newModel->event_date = $eventDate;
                    if (empty($newModel->event_date)) {
                        $newModel->event_date = $newModel->data_collection_date;
                    }
                    $this->saveAnimalEventModel($newModel, $k . $i, true);
                }

                # Register Calf & Calf Weight for Calving Event
                if ($eventType ===1){
                    #Get flag that determines if calf + weight should be registered
                    $is_calf_registered = $this->getFormDataValueByKey($data, $calfRegistrationAttributeKey);
                    #Check if calf is to be Created
                    if($is_calf_registered==="1"){
                        #Register Calf
                        $this->registerCalfAfterCalving($data,$repeatKey, $groupKey,$animalCode,$params);
                        #Register Calf Weight
                    }
                }

            }
        }

    }



    /**
     * @param null $eventType
     * @param null $repeatKey
     * @return int
     */
    protected function getFakeEventType($eventType,$repeatKey)
    {
        $transformedEventType = null;
        if($eventType ===6 && $repeatKey ==="cow_monitoring/cow_monitoringanimal"){
            #weight under milking
            $transformedEventType = 60;
        }else{
            $transformedEventType = $eventType;
        }
        return $transformedEventType;
    }

    /**
     * @param null $eventType
     * @return string
     */
    protected function getTagIdKeyAttribute($eventType)
    {
        #weight: Weight is captured in 3 different ways
        #1.Dedicated weight event
        #2.Under Milking -> Event ID will be 60
        #3.Under Calf registration
        if($eventType ===1 OR $eventType ===2 OR $eventType ===60 OR $eventType ===17 OR $eventType ===12 OR $eventType ===13 OR $eventType ===14 OR $eventType ===15){
            # milking|calving|weight(under milking| vaccination | Parasite Infection | Injury| hoof health)
            $tag_attribute ="cow_monitoring/cow_monitoringanimal/cowmonitor_animaldetails";
        }elseif($eventType ===4){
            # pd
            $tag_attribute = "animal_breeding/animal_breedingpd/breeding_pdanimaldetails";
        }elseif($eventType ===3){
            # ai
            $tag_attribute = "animal_breeding/animal_breedingai/breeding_aianimaldetails";
        }elseif($eventType ===5){
            # sync
            $tag_attribute = "animal_breeding/animal_breedingsync/breeding_syncanimaldetails";
        }elseif($eventType ===10){
            # hair sampling
            $tag_attribute = "animal_hairsampling/animal_hairsamplingdetails/hairsampling_animaldetails";
        }elseif($eventType ===12){
            # Vaccination: ECF
            $tag_attribute = "ecf_vaccination/ecf_vaccinationanimal/ecf_vaccinationanimaldetails";

        }else{
            $tag_attribute = null;
        }
        return $tag_attribute;
    }
    /**
     * @param string $animalCode
     * @param null $eventType
     * @param null $jsonData
     * @return  Animal|null
     */
    protected function getAnimalModelByOdkCode($animalCode,$jsonData,$eventType)
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
            $model = null;
            $model = $this->_animalsModels[$animalCode] ?? null;
            $country_id = $this->_model->country_id;
            if($jsonData){
                #get the attribute key with the tag id
                $tag_attribute = $this->getTagIdKeyAttribute($eventType);
                if($tag_attribute){
                    #get the actual tag id string
                    $tagIdString = $this->getFormDataValueByKey($jsonData, $tag_attribute);
                    if($tagIdString){
                        #Extract Tag ID from string
                        $tag_id = $this->getTagIDFormString($tagIdString);
                        if($tag_id){
                            $newAnimalModel = Animal::find()->andWhere(['tag_id' => $tag_id,'country_id' => $country_id])->one();
                            $model = $newAnimalModel ?? $this->_animalsModels[$animalCode] ?? null;
                        }
                    }
                }
            }

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
        $damCodeKey = self::getAttributeJsonKey('animal_damplatformuniqueid', 'animal_damknownlist', 'animal_general');
        $animalCode = $this->getFormDataValueByKey($animalData, $damCodeKey);
        $damModel = null;
        if (!empty($animalCode)) {
            $damModel = $this->getAnimalModelByOdkCode($animalCode,null,null);
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

//            $birthdateKey = OdkForm::isVersion1Point5OrBelow($this->_model->form_version) ? 'animal_damdobfull' : 'animal_damactualdob';
            $fixedAttributesMap = [
                'name' => self::getAttributeJsonKey('animal_damname', $damDetailGroupKey, $repeatKey),
                'tag_id' => self::getAttributeJsonKey('animal_damtagid', $damDetailGroupKey, $repeatKey),
                'main_breed' => self::getAttributeJsonKey('animal_dammainbreed', $damDetailGroupKey, $repeatKey),
                'main_breed_other' => self::getAttributeJsonKey('animal_dammainbreedoth', $damDetailGroupKey, $repeatKey),
                'breed_composition' => self::getAttributeJsonKey('animal_dammaincomp', $damDetailGroupKey, $repeatKey),
                'birthdate' => self::getAttributeJsonKey('animal_damactualdob', $damDetailGroupKey, $repeatKey),
            ];

//            foreach ($fixedAttributesMap as $attr => $odkKey) {
//                $animalModel->{$attr} = $this->getFormDataValueByKey($damData, $odkKey);
//            }
            foreach ($fixedAttributesMap as $attr => $odkKey) {
                // birthdate has to be processed separately
                // convert datetime to date first
                if ($attr == 'birthdate') {
                    $datetime = $this->getFormDataValueByKey($damData, $odkKey);
                    $birthdate = date('Y-m-d', strtotime($datetime));
                    $animalModel->{$attr} = $birthdate;
                } else {
                    $animalModel->{$attr} = $this->getFormDataValueByKey($damData, $odkKey);
                }
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
        $sireType = null;
        $sireModel = null;
        $sireCodeKey = self::getAttributeJsonKey('animal_sirebullplatformuniqueid', 'animal_sireknownlist', 'animal_general');
        $animalCode = $this->getFormDataValueByKey($animalData, $sireCodeKey);
        if (!empty($animalCode)) {
            $sireModel = $this->getAnimalModelByOdkCode($animalCode,null,null);
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
                    'birthdate' => self::getAttributeJsonKey('animal_sireactualdob', $sireBullDetailGroupKey, $repeatKey),
                    'sire_owner' => self::getAttributeJsonKey('animal_sireowner', $sireBullDetailGroupKey, $repeatKey),
                    'sire_owner_scheme' => self::getAttributeJsonKey('animal_sireownerscheme', $sireBullDetailGroupKey, $repeatKey),
                    'sire_owner_institute' => self::getAttributeJsonKey('animal_sireownerinstitute', $sireBullDetailGroupKey, $repeatKey),
                    'sire_owner_farmer' => self::getAttributeJsonKey('animal_sireownerfarmer', $sireBullDetailGroupKey, $repeatKey),
                    'sire_owner_farmer_phone' => self::getAttributeJsonKey('animal_sireownerfarmermobile', $sireBullDetailGroupKey, $repeatKey),
                ];
            }

//            foreach ($fixedAttributesMap as $attr => $odkKey) {
//                $animalModel->{$attr} = $this->getFormDataValueByKey($sireData, $odkKey);
//            }

            foreach ($fixedAttributesMap as $attr => $odkKey) {
                // birthdate has to be processed separately
                // convert datetime to date first
                if ($attr == 'birthdate') {
                    $datetime = $this->getFormDataValueByKey($sireData, $odkKey);
                    $birthdate = date('Y-m-d', strtotime($datetime));
                    $animalModel->{$attr} = $birthdate;
                } else {
                    $animalModel->{$attr} = $this->getFormDataValueByKey($sireData, $odkKey);
                }
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
        // Check if the country_id is 12
        if ($model->country_id === 12) {
            // Find an existing animal record based on the tag_id
            $existingModel = Animal::find()->andWhere(['tag_id' => $model->tag_id])->one();

            if ($existingModel !== null) {
                // If an existing animal is found, update its attributes with the new model's attributes
                // excluding the 'id' attribute to prevent duplicate primary key errors
                $existingModel->ignoreAdditionalAttributes = false;
                foreach ($model->safeAttributes() as $attr) {
                    if ($attr !== 'id') {
                        $existingModel->{$attr} = $model->{$attr};
                    }
                }

                $model = $existingModel; // Set the new model to the existing model for saving/updating
            }
        }

        // Save the animal model and get the saved data and model
        $data = $this->saveModel($model, $validate);

        // Update the arrays holding data and models for further processing (e.g., calving events)
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
        $index = $model->event_type . '_' . $index;
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
        if ($model->hasErrors()) {
            $this->_model->has_errors = 1;
            $this->_model->error_message = get_class($model) . ": validation errors";
        }
        return [
            'model' => $model,
            'data' => ['attributes' => $model->attributes, 'errors' => $isSaved ? null : $model->getErrors(), 'id' => $model->id]
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