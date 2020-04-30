<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-11-08
 * Time: 2:54 PM
 */

namespace backend\modules\core\models;


use common\helpers\DateUtils;
use common\helpers\Lang;

trait AnimalEventValidators
{
    public function validateCalvingDate($attribute, $params)
    {
        if ($this->event_type != AnimalEvent::EVENT_TYPE_CALVING || $this->hasErrors()) {
            return false;
        }
        if (!empty($this->{$attribute})) {

            $eventDate = $this->{$attribute};
            $lastCalving = static::getLastAnimalEvent($this->animal_id, self::EVENT_TYPE_CALVING);
            if (null === $lastCalving) {
                return true;
            }
            $dateDiff = DateUtils::getDateDiff($eventDate, $lastCalving->event_date);
            $minDays = 220;
            $maxDays = 1500;
            if ($dateDiff->days < $minDays) {
                $this->addError($attribute, Lang::t("Calving date interval should not be less than {minDays} days.", [
                    'minDays' => $minDays,
                ]));
            }
            if ($dateDiff->days > $maxDays) {
                $this->addError($attribute, Lang::t("Calving date interval should not be more than {maxDays} days.", [
                    'maxDays' => $maxDays,
                ]));
            }
        }
    }

    public function validateMilkingDate($attribute, $params)
    {
        if ($this->event_type !== AnimalEvent::EVENT_TYPE_MILKING || $this->hasErrors()) {
            return;
        }
        if (!empty($this->{$attribute})) {
            $eventDate = $this->{$attribute};
            //$interval = 500;
            $interval = 1000;
            $condition = '[[animal_id]]=:animal_id AND [[event_type]]=:event_type AND ([[event_date]] BETWEEN (:event_date - INTERVAL :interval DAY) AND :event_date)';
            $params = [':animal_id' => $this->animal_id, ':event_type' => AnimalEvent::EVENT_TYPE_CALVING, ':event_date' => $eventDate, ':interval' => $interval];
            if (!static::exists($condition, $params)) {
                $this->addError($attribute, Lang::t("The animal has not calved within the last {interval} days", [
                    'interval' => $interval,
                ]));
            }
        }
    }
}