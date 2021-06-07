<?php


namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use common\helpers\DbUtils;
use yii\helpers\ArrayHelper;

/**
 * Class FeedingEvent
 * @package backend\modules\core\models
 *
 * @property string $concetrate_propotion
 * @property string $feed_lactation
 * @property string $fodder_acres
 * @property string $fodder_propotion
 * @property string $residue_source
 */
class FeedingEvent extends AnimalEvent  implements ImportActiveRecordInterface, AnimalEventInterface
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
            'event_date' => 'Feeding Date',
        ]);
    }

    /**
     * @return string[]
     */
    public function getExcelColumns()
    {
        return [
            'animalTagId',
            'event_date',
            'concentrate_propotion',
            'feed_lactation',
            'fodder_acres',
            'residue_propotion',
            'residue_source',
        ];
    }


    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_FEEDING;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['lactation_id', 'lactation_number'];
    }
}