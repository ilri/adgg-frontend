<?php

namespace backend\modules\core\models;

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
 * @property string $created_at
 * @property int|null $created_by
 *
 * @property Country $country
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
            [['is_processed', 'country_id', 'has_errors'], 'integer'],
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

            return true;
        }
        return false;
    }

    protected function setCountryId()
    {
        if ($this->form_version === self::ODK_FORM_VERSION_1_POINT_4) {
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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            //ODKFormProcessor::push(['itemId' => $this->id]);
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
}
