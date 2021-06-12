<?php


namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use yii\helpers\ArrayHelper;

/**
 * Class InjuryEventController
 * @package backend\modules\core\models
 *
 * @property string $hoof_dd
 * @property string $hoof_ih
 * @property string $hoof_ip
 * @property string $hoof_sc
 * @property string $hoof_hfh
 * @property string $hoof_vfh
 * @property string $hoof_sw
 * @property string $hoof_hhe
 * @property string $hoof_other_problems
 */
class HoofHealthEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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
            'event_date' => 'Hoof Health Date',
        ]);
    }


    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_HOOF_HEALTH;
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
            'hoof_dd',
            'hoof_ih',
            'hoof_ip',
            'hoof_sc',
            'hoof_hfh',
            'hoof_vfh',
            'hoof_sw',
            'hoof_hhe',
            'hoof_other_problems',
        ];
    }
}