<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-15
 * Time: 8:49 AM
 */

namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use common\helpers\ArrayHelper;
use common\helpers\DbUtils;
use Yii;

/**
 * Class MilkingEvent
 * @package backend\modules\core\models
 *
 * @property float $milkmor
 * @property float $milkeve
 * @property float $milkmid
 * @property float $milkday
 * @property float $milkfat
 * @property float $milkprot
 * @property float $milklact
 * @property float $milksmc
 * @property float $milkurea
 * @property int $milk_sample_type
 * @property string $dry_date
 * @property string $milk_notes
 * @property int $dim
 * @property int $testday_no
 */
class MilkingEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['milkmor', 'milkmid', 'milkmid'], 'number', 'min' => 0, 'max' => 30, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            [['milkday'], 'number', 'min' => 0, 'max' => 60, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            [['milkfat'], 'number', 'min' => 0.5, 'max' => 10, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            [['milkprot'], 'number', 'min' => 0.5, 'max' => 6, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            [['milksmc'], 'number', 'min' => 15000, 'max' => 99999999999, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            ['milkurea', 'number', 'min' => 8, 'max' => 25, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            ['milklact', 'number', 'min' => 2, 'max' => 6, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            // ['event_date', 'validateMilkingDate', 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            [$this->getExcelColumns(), 'safe', 'on' => self::SCENARIO_UPLOAD],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'event_date' => 'Milk Date',
        ]);
    }

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'animal_id',
            'field_agent_id',
            'milk_cow_status',
            'dry_date',
            'milk_calving_date',
            'event_date',
            'milk_date',
            'milkmor',
            'milkmid',
            'milkeve',
            'milk_quality',
            'milk_sample_type',
            'milkfat',
            'milkprot',
            'milkurea',
            'milklact',
            'milksmc',
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
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_MILKING, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }

    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_MILKING;
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