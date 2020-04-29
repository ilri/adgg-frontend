<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_odk_json_queue".
 *
 * @property int $id
 * @property string $form_uuid
 * @property string|array $form_data
 * @property int $is_processed
 * @property string $processed_at
 * @property int $country_id
 * @property int $has_errors
 * @property string $error_message
 * @property string|array $error_json
 * @property string $created_at
 * @property int $created_by
 */
class OdkJsonQueue extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait, CountryDataTrait;

    const SCENARIO_UPLOAD = 'upload';
    const SCENARIO_API_PUSH = 'api_push';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_odk_json_queue}}';
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
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
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
            'error_json' => 'Error JSON',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['form_uuid', 'form_uuid'],
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
            $this->setCountryId();

            return true;
        }
        return false;
    }

    protected function setCountryId()
    {
        if (!empty($this->form_data['activities_country'])) {
            $countryId = Country::getScalar('id', ['code' => $this->form_data['activities_country']]);
            if (!empty($countryId)) {
                $this->country_id = $countryId;
            }
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            //ProcessODKJson::push(['queueId' => $this->id]);
        }
    }


    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            return true;
        }
        return false;
    }
}
