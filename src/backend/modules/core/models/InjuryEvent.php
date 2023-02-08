<?php


namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use yii\helpers\ArrayHelper;

/**
 * Class InjuryEventController
 * @package backend\modules\core\models
 *
 * @property string $injury_type
 * @property string $injury_type_other
 * @property string $injury_service_provider
 * @property string $injury_service_provider_other
 * @property string $injury_drug_cost
 * @property string $injury_service_cost
 * @property string $injury_cow_status
 * @property string $injury_cow_status_other
 */
class InjuryEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
{

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [$this->getExcelColumns(), 'safe', 'on' => self::SCENARIO_UPLOAD],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'event_date' => 'Vaccination Date',
        ]);
    }


    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_INJURY;
    }

    /**
     * @return string[]
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['lactation_id', 'lactation_number'];
    }

    /**
     * @return string[]
     */
    public function getExcelColumns()
    {
        return [
            'animalTagId',
            'event_date',
            'injury_type',
            'injury_type_other',
            'injury_service_provider',
            'injury_service_provider_other',
            'injury_drug_cost',
            'injury_service_cost',
            'injury_cow_status',
            'injury_cow_status_other',
        ];
    }
}