<?php


namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use yii\helpers\ArrayHelper;

/**
 * Class VaccinationEvent
 * @package backend\modules\core\models
 *
 * @property string $vacc_vaccinetype
 * @property string $vacc_othervaccinetype
 * @property string $vacc_vaccineprovider
 * @property string $vacc_vaccineproviderother
 * @property string $vacc_vaccinedrugcost
 * @property string $vacc_vaccineservicecost
 * @property string $vacc_vaccinecowstatus
 * @property string $vacc_vaccinecowstatusother
 */
class VaccinationEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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
        return self::EVENT_TYPE_VACCINATION;
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
            'vacc_vaccinetype',
            'vacc_othervaccinetype',
            'vacc_vaccineprovider',
            'vacc_vaccineproviderother',
            'vacc_vaccinedrugcost',
            'vacc_vaccineservicecost',
            'vacc_vaccinecowstatus',
            'vacc_vaccinecowstatusother',
        ];
    }
}