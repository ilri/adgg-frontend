<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-08-27
 * Time: 4:35 PM
 */

namespace backend\modules\core\models;


class FarmMetadataConcentrateFeeding extends FarmMetadata implements FarmMetadataInterface
{
    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_CONCENTRATES;
    }
}