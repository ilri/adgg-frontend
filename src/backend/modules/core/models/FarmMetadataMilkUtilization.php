<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-08-27
 * Time: 2:46 PM
 */

namespace backend\modules\core\models;


class FarmMetadataMilkUtilization extends FarmMetadata implements FarmMetadataInterface
{
    public static function getDefineMetadataType(): int
    {
        return self::TYPE_MILK_UTILIZATION;
    }
}