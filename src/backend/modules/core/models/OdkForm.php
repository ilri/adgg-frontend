<?php

namespace backend\modules\core\models;

use backend\modules\auth\models\Users;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use console\jobs\ODKFormProcessor;

/**
 * This is the model class for table "core_odk_form".
 *
 * @property int $id
 * @property string $form_uuid
 * @property string|array $form_data
 * @property int $is_processed
 * @property string|null $processed_at
 * @property int|null $country_id
 * @property int $has_errors
 * @property string|null $error_message
 * @property string|array|null $farm_data
 * @property string|array|null $farm_metadata
 * @property string|array|null $animals_data
 * @property string|array|null $animal_events_data
 * @property string|array|null $user_data
 * @property string|null $form_version
 * @property int|null $user_id
 * @property string $created_at
 * @property int|null $created_by
 *
 * @property Country $country
 * @property Users $user
 */
class OdkForm extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait, CountryDataTrait;

    const SCENARIO_UPLOAD = 'upload';
    const SCENARIO_API_PUSH = 'api_push';

    //ODK FORM VERSIONS
    const ODK_FORM_VERSION_1_POINT_4 = 'Ver 1.4';
    const ODK_FORM_VERSION_1_POINT_5 = 'Ver 1.5';
    const ODK_FORM_VERSION_1_POINT_6 = 'Ver 1.6';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_odk_form}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['form_data'], 'required'],
            [['is_processed', 'country_id', 'has_errors', 'user_id'], 'integer'],
            [['form_uuid'], 'string', 'max' => 128],
            [[self::SEARCH_FIELD, 'form_version'], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'form_uuid' => 'Form UUID',
            'form_data' => 'Form Data',
            'is_processed' => 'Is Processed',
            'processed_at' => 'Processed At',
            'country_id' => 'Country',
            'has_errors' => 'Has Errors',
            'error_message' => 'Error Message',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'farm_data' => 'Farm Data',
            'farm_metadata' => 'Farm Metadata',
            'animals_data' => 'Animals Data',
            'animal_events_data' => 'Animal Events Data',
            'user_data' => 'User Data',
            'form_version' => 'Form Version',
            'user_id' => 'User Id',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['form_uuid', 'form_uuid'],
            ['form_version', 'form_version'],
            'is_processed',
            'country_id',
            'has_errors',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (is_string($this->form_data)) {
                $this->form_data = json_decode($this->form_data, true);
            }
            $this->form_uuid = $this->form_data['_uuid'] ?? null;
            $this->form_version = $this->form_data['_version'] ?? null;
            $this->setCountryId();
            $this->setUserId();
            if (empty($this->error_message)) {
                $this->setErrorMessage();
            }

            return true;
        }
        return false;
    }

    protected function setCountryId()
    {
        if (!empty($this->country_id)) {
            return;
        }
        if ($this->isVersion1Point4()) {
            $jsonKey = 'activities_country';
        } else {
            $jsonKey = 'activities_location/activities_country';
        }
        $code = $this->form_data[$jsonKey] ?? null;
        if (!empty($code)) {
            $countryId = Country::getScalar('id', ['code' => $code]);
            if (!empty($countryId)) {
                $this->country_id = $countryId;
            }
        }
    }

    protected function setErrorMessage()
    {
        $errorMessage = [];
        if (!empty($this->farm_data)) {
            foreach ($this->farm_data as $i => $farmData) {
                if (!empty($farmData['errors'])) {
                    $errorMessage[] = Farm::class . ": Validation errors.";
                    break;
                }
            }
        }
        if (!empty($this->farm_metadata)) {
            foreach ($this->farm_metadata as $i => $farmMetaData) {
                if (!empty($farmMetaData['errors'])) {
                    $errorMessage[] = FarmMetadata::class . ": Validation errors.";
                    break;
                }
            }
        }
        if (!empty($this->animals_data)) {
            foreach ($this->animals_data as $i => $animalData) {
                if (!empty($animalData['errors'])) {
                    $errorMessage[] = Animal::class . ": Validation errors.";
                    break;
                }
            }
        }
        if (!empty($this->animal_events_data)) {
            foreach ($this->animal_events_data as $i => $animalEventData) {
                if (!empty($animalEventData['errors'])) {
                    $errorMessage[] = AnimalEvent::class . ": Validation errors.";
                    break;
                }
            }
        }
        if (!empty($this->user_data)) {
            foreach ($this->user_data as $i => $userData) {
                if (!empty($userData['errors'])) {
                    $errorMessage[] = Users::class . ": Validation errors.";
                    break;
                }
            }
        }

        if (!empty($errorMessage)) {
            $this->has_errors = 1;
            $this->error_message = implode(', ', $errorMessage);
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            ODKFormProcessor::push(['itemId' => $this->id]);
        }
    }


    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            return true;
        }
        return false;
    }

    /**
     * @param string $versionString
     * @return float
     */
    public static function getVersionNumber($versionString)
    {
        return (float)filter_var($versionString, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * @return bool
     */
    public function isVersion1Point4()
    {
        return $this->form_version === self::ODK_FORM_VERSION_1_POINT_4;
    }

    /**
     * @return bool
     */
    public function isVersion1Point5()
    {
        return $this->form_version === self::ODK_FORM_VERSION_1_POINT_5;
    }

    /**
     * @return bool
     */
    public function isVersion1Point6()
    {
        return $this->form_version === self::ODK_FORM_VERSION_1_POINT_6;
    }

    /**
     * @param string $versionString
     * @return bool
     */
    public static function isVersion1Point5OrBelow($versionString)
    {
        $version1Point5 = static::getVersionNumber(static::ODK_FORM_VERSION_1_POINT_5);
        $givenVersion = OdkForm::getVersionNumber($versionString);

        return ($givenVersion <= $version1Point5);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    protected function setUserId()
    {
        if (!empty($this->user_id)) {
            return;
        }
        $jsonKey = 'staff_code';
        $code = $this->form_data[$jsonKey] ?? null;
        $id = Users::getScalar('id', ['odk_code' => $code, 'country_id' => $this->country_id]);
        if (empty($id)) {
            $id = null;
        }
        $this->user_id = $id;
    }
}
