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
 * @property float $straw_id
 * @property float $straw_semen_type
 * @property float $sire_registered_name
 * @property float $sire_short_name
 * @property float $herd_book_number
 * @property int $semen_source
 * @property string $semen_supplier
 * @property string $semen_source_type
 * @property string $sire_genomic_estimated_breeding_value
 */
class StrawEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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
            'event_date' => 'Collection Date',
        ]);
    }


    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return[
            'animal_id',
            'event_date',
            'straw_id',
            'straw_semen_type',
            'sire_registered_name',
            'sire_short_name',
            'herd_book_number',
            'semen_source',
            'semen_supplier',
            'semen_source_type',
            'sire_genomic_estimated_breeding_value',
            'field_agent_id'
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
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_STRAW, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }

    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_STRAW;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['lactation_id', 'lactation_number'];
    }
}