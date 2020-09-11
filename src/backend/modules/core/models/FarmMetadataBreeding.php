<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-05-18
 * Time: 8:51 PM
 */

namespace backend\modules\core\models;


class FarmMetadataBreeding extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefineMetadataType(): int
    {
        return self::TYPE_BREEDING_TECHNOLOGIES;
    }
}