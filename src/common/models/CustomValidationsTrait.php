<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-08-13
 * Time: 3:52 AM
 */

namespace common\models;


use common\helpers\DateUtils;
use common\helpers\Lang;

trait CustomValidationsTrait
{
    public function validateNoFutureDate($attribute, $params)
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->{$attribute})) {
            if (strtotime($this->{$attribute}) > strtotime(DateUtils::getToday())) {
                $this->addError($attribute, Lang::t('{attribute} must not be a future date.', [
                    'attribute' => $this->getAttributeLabel($attribute),
                ]));
            }
        }
    }
}