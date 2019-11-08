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
 * @property float $milkday
 * @property float $mlkfat
 * @property float $mlkprot
 * @property float $milklact
 * @property float $mlksmc
 * @property float $milkurea
 *
 */
class MilkingEvent extends AnimalEvent implements ImportActiveRecordInterface
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
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