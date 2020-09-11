<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-03
 * Time: 4:56 PM
 */

namespace backend\modules\core\models;


class FarmMetadataWaterSource extends FarmMetadata implements FarmMetadataInterface
{
    public static function getDefineMetadataType(): int
    {
        return self::TYPE_WATER_SOURCE;
    }
}