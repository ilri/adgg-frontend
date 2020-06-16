<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-06-12
 * Time: 2:06 PM
 */

namespace backend\modules\core\models;


class FarmMetadataHouseholdMembers extends FarmMetadata
{

    public static function getDefineMetadataType(): int
    {
        return self::TYPE_HOUSEHOLD_MEMBERS;
    }
}