<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-05-28
 * Time: 2:34 PM
 */

namespace backend\modules\core\models;


class FarmMetadataBreedingOtherBulls extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_BREEDING_OTHER_BULLS;
    }
}