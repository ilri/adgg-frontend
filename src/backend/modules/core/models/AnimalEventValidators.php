<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-11-08
 * Time: 2:54 PM
 */

namespace backend\modules\core\models;


use common\helpers\Lang;

trait AnimalEventValidators
{
    public function validateCalvingDate($attribute, $params)
    {
        if ($this->event_type != AnimalEvent::EVENT_TYPE_CALVING || $this->hasErrors()) {
            return false;
        }
        if (!empty($this->{$attribute})) {
            $minDays = 220;
            $maxDays = 1500;
            //last calving days should not be less than 220 days
            //EXAMPLE SELECT * FROM  core_animal_event where animal_id="2483" AND event_type=1 AND DATEDIFF('2018-11-30',event_date)<220;
            $condition = "[[animal_id]]=:animal_id AND [[event_type]]=:event_type AND DATEDIFF(:event_date,[[event_date]])>0 AND DATEDIFF(:event_date,[[event_date]])<:min_days";
            $params = [':animal_id' => $this->animal_id, ':event_type' => $this->event_type, ':event_date' => $this->event_date, ':min_days' => $minDays];
            if (static::exists($condition, $params)) {
                $this->addError($attribute, Lang::t("Last calving date interval should not be less than {minDays} days.", [
                    'minDays' => $minDays,
                ]));
            }
            //next calving days should not be more than 1500 days
            //EXAMPLE: SELECT * FROM core_animal_event where animal_id="2483" AND event_type=1 AND DATEDIFF(event_date,'2017-06-06')>0 AND DATEDIFF(event_date,'2017-06-06')>1500;
            $condition = "[[animal_id]]=:animal_id AND [[event_type]]=:event_type AND DATEDIFF([[event_date]],:event_date)>:max_days";
            $params = [':animal_id' => $this->animal_id, ':event_type' => $this->event_type, ':event_date' => $this->event_date, ':max_days' => $maxDays];
            if (static::exists($condition, $params)) {
                $this->addError($attribute, Lang::t("Next calving date interval should not be more than {maxDays} days.", [
                    'maxDays' => $maxDays,
                ]));
            }
        }
    }

    public function validateMilkingDate($attribute, $params)
    {
        if ($this->event_type != AnimalEvent::EVENT_TYPE_MILKING || $this->hasErrors()) {
            return;
        }
        if (!empty($this->{$attribute})) {
            $eventDate = $this->{$attribute};
            //$interval = 500;
            $interval = 1000;
            $condition = '[[animal_id]]=:animal_id AND [[event_type]]=:event_type AND ([[event_date]] BETWEEN (:event_date - INTERVAL :interval DAY) AND :event_date)';
            $params = [':animal_id' => $this->animal_id, ':event_type' => AnimalEvent::EVENT_TYPE_CALVING, ':event_date' => $eventDate, ':interval' => $interval];
            if (!static::exists($condition, $params)) {
                $this->addError($attribute, Lang::t("The animal has not calved within the last {interval} days Condition: {condition}, params: {params}", [
                    'interval' => $interval,
                    'condition' => $condition,
                    'params' => implode($params),
                ]));
            }
        }
    }
}