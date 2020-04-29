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
use common\helpers\DateUtils;
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
 * @property float $milk_estimated_weight
 * @property float $milk_heartgirth
 * @property float $milk_bodyscore
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
            [['milkfat'], 'number', 'min' => 1.5, 'max' => 9, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            [['milkprot'], 'number', 'min' => 1.5, 'max' => 5, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            [['milksmc'], 'number', 'min' => 30000, 'max' => 99999999999, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            ['milkurea', 'number', 'min' => 8, 'max' => 25, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            ['milklact', 'number', 'min' => 3, 'max' => 7, 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
            ['event_date', 'validateMilkingDate', 'except' => [self::SCENARIO_MISTRO_DB_UPLOAD]],
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
            'milk_bodyscore',
            'milkurea',
            'milklact',
            'milk_heartgirth',
            'weight',
            'milk_estimated_weight',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ignoreAdditionalAttributes = false;
            if (empty($this->milkday)) {
                $this->milkday = ((float)$this->milkmor + (float)$this->milkeve + (float)$this->milkmid);
            }
            $this->setDIM();
            $this->ignoreAdditionalAttributes = true;

            return true;
        }
        return false;
    }

    protected function setDIM()
    {
        if ($this->event_type != self::EVENT_TYPE_MILKING || null === $this->lactation || empty($this->lactation->event_date) || empty($this->event_date)) {
            return;
        }
        $diff = DateUtils::getDateDiff($this->lactation->event_date, $this->event_date);
        $this->dim = $diff->days;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->setTestDayNumber();
    }


    protected function setTestDayNumber()
    {
        if ($this->event_type != self::EVENT_TYPE_MILKING || null === $this->lactation) {
            return;
        }
        if ($this->scenario == self::SCENARIO_MISTRO_DB_UPLOAD) {
            return;
        }

        $data = static::getData(['id'], ['event_type' => self::EVENT_TYPE_MILKING, 'animal_id' => $this->animal_id, 'lactation_id' => $this->lactation_id], [], ['orderBy' => ['event_date' => SORT_ASC]]);
        $n = 1;
        foreach ($data as $row) {
            static::updateAll(['testday_no' => $n], ['id' => $row['id']]);
            $n++;
        }
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