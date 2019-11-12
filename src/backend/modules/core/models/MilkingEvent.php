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
 * @property int $milk_qty_tested
 * @property int $milk_sample_type
 *
 */
class MilkingEvent extends AnimalEvent implements ImportActiveRecordInterface
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
            'animalTagId',
            'event_date',
            'milkmor',
            'milkmid',
            'milkeve',
            'milk_qty_tested',
            'milk_sample_tested',
            'mlkfat',
            'mlkprot',
            'mlksmc',
            'milkurea',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->milkday)) {
                $this->milkday = ((float)$this->milkmor + (float)$this->milkeve + (float)$this->milkmid);
            }
            return true;
        }
        return false;
    }


}