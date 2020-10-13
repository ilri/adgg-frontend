<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-11
 * Time: 10:48 PM
 */

namespace backend\modules\core\models;


class FarmMetadataExtensionServices extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_FARM_EXTENSION_SERVICES;
    }
}