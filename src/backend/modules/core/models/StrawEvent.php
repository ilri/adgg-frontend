<?php

namespace backend\modules\core\models;

use common\excel\ImportActiveRecordInterface;
use common\helpers\ArrayHelper;
use common\helpers\DbUtils;
use Yii;

/**
 *Class StrawEvent
 * @package backend\modules\core\models
 *
 * @property float $straw_sire_type
 * @property float $straw_semen_type
 * @property float $sire_registered_name
 * @property float $sire_short_name
 * @property float $herd_book_number
 * @property float $straw_id
 * @property float $straw_main_breed
 * @property float $straw_secondary_breed
 * @property float $straw_main_breed_composition
 * @property int $semen_source
 * @property string $semen_supplier
 * @property string $semen_source_type
 * @property string $sire_genomic_estimated_breeding_value
 * @property float $straw_sire_id
 * @property float $straw_sire_name
 * @property string $sraw_dam_id
 * @property int $straw_dam_name
 */
class StrawEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
//            [['milkmor', 'milkmid', 'milkmid'], 'number', 'min' => 0, 'max' => 30, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
//            [['milkday'], 'number', 'min' => 0, 'max' => 60, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
//            [['milkfat'], 'number', 'min' => 0.5, 'max' => 10, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
//            [['milkprot'], 'number', 'min' => 0.5, 'max' => 6, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
//            [['milksmc'], 'number', 'min' => 15000, 'max' => 99999999999, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
//            ['milkurea', 'number', 'min' => 8, 'max' => 25, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
//            ['milklact', 'number', 'min' => 2, 'max' => 6, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
//            //['milk_calvdate', 'validateCalvingDate', 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
//            //['event_date', 'validateMilkingDate', 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
//            [$this->getExcelColumns(), 'safe', 'on' => self::SCENARIO_UPLOAD],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'event_date' => 'Sraw Entry Date',
        ]);
    }


    public function getEventType(): int
    {
        return self::EVENT_TYPE_STRAW_EVENT;
    }

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return[
            'straw_sire_type',
            'straw_semen_type',
            'sire_registered_name',
            'sire_short_name',
            'herd_book_number',
            'straw_id',
            'straw_main_breed',
            'straw_secondary_breed',
            'straw_main_breed_composition',
            'semen_source',
            'semen_supplier',
            'semen_source_type',
            'sire_genomic_estimated_breeding_value',
            'straw_sire_id',
            'straw_sire_name',
            'sraw_dam_id',
            'straw_dam_name'
        ];
    }

    /**
     * @param integer $durationType
     * @param bool|string $sum
     * @param array $filters array key=>$value pair where key is the attribute name and value is the attribute value
     * @param string $dateField
     * @param null|string $from
     * @param null|string $to
     * @param mixed $condition
     * @param array $params
     * @return int
     * @throws \Exception
     */
    public static function getDashboardStats($durationType, $sum = false, $filters = [], $dateField = 'event_date', $from = null, $to = null, $condition = '', $params = [])
    {
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_STRAW_EVENT, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderRelations()
    {
        return array_merge(['lactation'], parent::reportBuilderRelations());
    }
}