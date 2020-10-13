<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-05-28
 * Time: 3:20 PM
 */

namespace backend\modules\core\models;


class FarmMetadataBreedingSchemeBulls extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_BREEDING_SCHEME_BULLS;
    }
}