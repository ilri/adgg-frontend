<?php


namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use common\helpers\DbUtils;

/**
 * Class HairsamplingEvent
 * @package backend\modules\core\models
 *
 * @property string hair_sampling_code
 */
class HairsamplingEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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
            'event_date' => 'Hairsampling Date',
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
            'hair_sampling_code',
        ];
    }
}