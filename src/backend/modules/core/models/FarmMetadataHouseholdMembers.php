<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-06-12
 * Time: 2:06 PM
 */

namespace backend\modules\core\models;

/**
 * Class FarmMetadataHouseholdMembers
 * @package backend\modules\core\models\
 *
 * @property string|NULL hhh_name
 * @property string|NULL $hhh_mobile
 * @property string|NULL $hhh_gender
 * @property string|NULL $hhh_age
 * @property string|NULL $hhh_age_range
 * @property string|NULL $hhmember_rltshiphhhoth
 * @property string|NULL $hhmember_rltshiphhh
 */
class FarmMetadataHouseholdMembers extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_HOUSEHOLD_MEMBERS;
    }
}