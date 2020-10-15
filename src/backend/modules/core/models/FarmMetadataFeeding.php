<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-05-12
 * Time: 1:31 PM
 */

namespace backend\modules\core\models;


class FarmMetadataFeeding extends FarmMetadata implements FarmMetadataInterface
{
    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_FEEDING_SYSTEMS;
    }
}