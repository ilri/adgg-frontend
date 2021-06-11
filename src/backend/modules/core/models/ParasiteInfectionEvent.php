<?php


namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use yii\helpers\ArrayHelper;

/**
 * Class ParasiteInfectionEvent
 * @package backend\modules\core\models
 *
 * @property string $parasite_type
 * @property string $parasite_type_other
 * @property string $parasite_provider
 * @property string $parasite_provider_other
 * @property string $parasite_drug_cost
 * @property string $parasite_service_cost
 * @property string $parasite_cow_status
 * @property string $parasite_cow_status_other
 */
class ParasiteInfectionEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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
            'event_date' => 'Parasite Infection Date',
        ]);
    }


    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_PARASITE_INFECTION;
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
            'parasite_type',
            'parasite_type_other',
            'parasite_provider',
            'parasite_provider_other',
            'parasite_drug_cost',
            'parasite_service_cost',
            'parasite_cow_status',
            'parasite_cow_status_other',
        ];
    }
}