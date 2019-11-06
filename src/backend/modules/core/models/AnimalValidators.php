<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-11-06
 * Time: 5:03 PM
 */

namespace backend\modules\core\models;


use common\helpers\DateUtils;
use common\helpers\Lang;

trait AnimalValidators
{
    public function validateCalvDate($attribute, $params)
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->{$attribute}) && !empty($this->birthdate)) {
            $dateDiff = DateUtils::getDateDiff($this->birthdate, $this->{$attribute});
            $minDays = 660;
            if ($dateDiff->days <= $minDays) {
                $this->addError($attribute, Lang::t("{attribute} must be > {minDays} days after {birthdate_label}", [
                    'attribute' => $this->getAttributeLabel($attribute),
                    'minDays' => $minDays,
                    'birthdate_label' => $this->getAttributeLabel('birthdate'),
                ]));
            }
        }
    }

    public function validateSireOrDam($attribute, $params)
    {
        if ($this->hasErrors()) {
            return false;
        }
        //animal cannot be a sire or dam to itself
        if (!empty($this->{$attribute})) {
            if ($this->tag_id == $this->{$attribute}) {
                $this->addError($attribute, Lang::t("{attribute} cannot be similar to {tagId}. An animal cannot be a sire or a dam to itself.", [
                    'attribute' => $this->getAttributeLabel($attribute),
                    'tagId' => $this->getAttributeLabel('tag_id'),
                ]));
            }
        }
    }

    public function validateSireBisexual($attribute, $params)
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->{$attribute})) {
            //check if the same sire_tag_id exist as a dam_tag_id
            if (static::exists(['dam_tag_id' => $this->{$attribute}, 'org_id' => $this->org_id])) {
                $this->addError($attribute, Lang::t("{attribute} already exists in the database as a dam and cannot be added as a sire.", [
                    'attribute' => $this->getAttributeLabel($attribute),
                ]));
            }
        }
    }

    public function validateDamBisexual($attribute, $params)
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->{$attribute})) {
            //check if the same dam_tag_id exist as a sire_tag_id
            if (static::exists(['sire_tag_id' => $this->{$attribute}, 'org_id' => $this->org_id])) {
                $this->addError($attribute, Lang::t("{attribute} already exists in the database as a sire and cannot be added as a dam.", [
                    'attribute' => $this->getAttributeLabel($attribute),
                ]));
            }
        }
    }
}