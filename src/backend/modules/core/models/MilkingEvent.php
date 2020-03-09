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
 * @property float $weight
 */
class MilkingEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['milkmor', 'milkmid', 'milkmid'], 'number', 'min' => 0, 'max' => 30],
            [['milkday'], 'number', 'min' => 0, 'max' => 60],
            [['milkfat'], 'number', 'min' => 1.5, 'max' => 9],
            [['milkprot'], 'number', 'min' => 1.5, 'max' => 5],
            [['milksmc'], 'number', 'min' => 30000, 'max' => 99999999999],
            ['milkurea', 'number', 'min' => 8, 'max' => 25],
            ['milklact', 'number', 'min' => 3, 'max' => 7],
            ['event_date', 'validateMilkingDate'],
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
     * @return \yii\db\ActiveQuery
     */
    public function getLactation()
    {
        return $this->hasOne(CalvingEvent::class, ['id' => 'lactation_id']);
    }

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'animalTagId',
            'dry_date',
            'event_date',
            'milkmor',
            'milkmid',
            'milkeve',
            'milk_quality',
            'milk_sample_type',
            'milkfat',
            'milkprot',
            'milksmc',
            'milkurea',
            'milklact',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ignoreAdditionalAttributes = false;
            if (empty($this->milkday)) {
                $this->milkday = ((float)$this->milkmor + (float)$this->milkeve + (float)$this->milkmid);
            }
            $this->ignoreAdditionalAttributes = true;

            return true;
        }
        return false;
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
}