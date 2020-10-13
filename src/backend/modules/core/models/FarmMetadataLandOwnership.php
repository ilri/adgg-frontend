<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-02
 * Time: 1:17 PM
 */

namespace backend\modules\core\models;


class FarmMetadataLandOwnership extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_LAND_OWNERSHIP;
    }
}