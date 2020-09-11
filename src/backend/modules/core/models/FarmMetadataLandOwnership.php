<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-02
 * Time: 1:17 PM
 */

namespace backend\modules\core\models;


class FarmMetadataLandOwnership extends FarmMetadata
{

    public static function getDefineMetadataType(): int
    {
        return self::TYPE_LAND_OWNERSHIP;
    }
}