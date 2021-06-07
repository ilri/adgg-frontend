<?php


namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use common\helpers\DbUtils;
use yii\helpers\ArrayHelper;

/**
 * Class FeedingEvent
 * @package backend\modules\core\models
 *
 * @property string $concentrate_cost
 * @property string $concetrate_feed_is_used
 * @property string $concetrate_information_source
 * @property string $concetrate_information_source_other
 * @property string $concetrate_propotion
 * @property string $concetrate_purchase_where
 * @property string $concetrate_purchase_where_other
 * @property string $concetrate_types
 * @property string $concetrate_types_other
 * @property string $crop_residue_feed_is_used
 * @property string $feeding_system_calves
 * @property string $feed_lactation
 * @property string $feeding_system_immature_heifers
 * @property string $feeding_system_immature_male
 * @property string $feeding_system_mature
 * @property string $fodder_acres
 * @property string $fodder_area_under_legumes
 * @property string $fodder_area_under_maize
 * @property string $fodder_area_under_naturalized_grasses
 * @property string $fodder_area_under_other
 * @property string $fodder_area_under_pennissetum
 * @property string $fodder_area_under_planted_grasses
 * @property string $fodder_area_under_shrubs
 * @property string $fodder_grow
 * @property string $fodder_growing_annual_cost
 * @property string $fodder_information_source
 * @property string $fodder_information_source_other
 * @property string $fodder_propotion
 * @property string $fodder_species
 * @property string $residue_propotion
 * @property string $residue_source
 * @property string $residue_types
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
            'concentrate_cost',
            'concentrate_feed_is_used',
            'concentrate_information_source',
            'concentrate_information_source_other',
            'concentrate_propotion',
            'concentrate_purchase_where',
            'concentrate_purchase_where_other',
            'concentrate_types',
            'concentrate_types_other',
            'crop_residue_feed_is_used',
            'feeding_system_calves',
            'feed_lactation',
            'feeding_system_immature_heifers',
            'feeding_system_immature_male',
            'feeding_system_mature',
            'fodder_acres',
            'fodder_area_under_legumes',
            'fodder_area_under_maize',
            'fodder_area_under_naturalized_grasses',
            'fodder_area_under_other',
            'fodder_area_under_pennissetum',
            'fodder_area_under_planted_grasses',
            'fodder_area_under_shrubs',
            'fodder_grow',
            'fodder_growing_annual_cost',
            'fodder_information_source',
            'fodder_information_source_other',
            'fodder_propotion',
            'fodder_species',
            'residue_propotion',
            'residue_source',
            'residue_types',
        ];
    }

    /**
     * @param int $durationType
     * @param false $sum
     * @param array $filters
     * @param string $dateField
     * @param null $from
     * @param null $to
     * @param string $condition
     * @param array $params
     * @return int
     * @throws \Exception
     */
    public static function getDashboardStats($durationType, $sum = false, $filters = [], $dateField = 'event_date', $from = null, $to = null, $condition = '', $params = [])
    {
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_FEEDING, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
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