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
        if ($this->event_type == AnimalEvent::EVENT_TYPE_CALVING || $this->hasErrors()) {
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
            if ($dateDiff->days < $minDays) {
                $this->addError($attribute, Lang::t("Calving date interval should not be less than {minDays} days.", [
                    'minDays' => $minDays,
                ]));
            }
        }
    }
}