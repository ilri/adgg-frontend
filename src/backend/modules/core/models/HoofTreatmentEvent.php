<?php



namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use yii\helpers\ArrayHelper;

/**
 * Class InjuryEventController
 * @package backend\modules\core\models
 *
 * @property string $hoof_problem
 * @property string $hoof_treatment_type
 * @property string $hoof_treatment_type_other
 * @property string $hoof_treatment_provider
 * @property string $hoof_treatment_provider_other
 * @property string $hoof_treatment_drug_cost
 * @property string $hoof_treatment_service_cost
 * @property string $hoof_treatment_cow_status
 * @property string $hoof_treatment_cow_status_other
 */
class HoofTreatmentEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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
            'event_date' => 'Hoof Treatment Date',
        ]);
    }


    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_HOOF_TREATMENT;
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
            'hoof_problem',
            'hoof_treatment_type',
            'hoof_treatment_type_other',
            'hoof_treatment_provider',
            'hoof_treatment_provider_other',
            'hoof_treatment_drug_cost',
            'hoof_treatment_service_cost',
            'hoof_treatment_cow_status',
            'hoof_treatment_cow_status_other',
        ];
    }
}