<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-28
 * Time: 2:24 PM
 */

namespace backend\modules\core\models;


use common\helpers\ArrayHelper;

/**
 * Class ExitsEvent
 * @package backend\modules\core\models
 *
 *
 */
class ExitsEvent extends AnimalEvent implements AnimalEventInterface
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
            'event_date' => 'Disposal Date',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_EXITS;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['lactation_id', 'lactation_number'];
    }

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return [
//            'animalTagId',
            'event_date',
//            'disposal_reason',
//            'disposal_reason_other',
//            'disposal_amount',
//            'new_country',
//            'new_region',
//            'new_district',
//            'new_ward',
//            'new_village',
//            'new_farmer_phone',
//            'new_farmer_name',
//            'new_breeder_name',
//            'new_breeder_phone',
            'exit_animalid',
            'old_farm_id',
            'new_farmer_id',
        ];
    }
}