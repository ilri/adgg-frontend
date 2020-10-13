<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-11
 * Time: 5:39 PM
 */

namespace backend\modules\core\models;


class FarmMetadataGroupMembership extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_GROUP_MEMBERSHIP;
    }
}