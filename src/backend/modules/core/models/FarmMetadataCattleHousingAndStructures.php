<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-11
 * Time: 6:14 PM
 */

namespace backend\modules\core\models;


class FarmMetadataCattleHousingAndStructures extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_CATTLE_HOUSING_AND_STRUCTURES;
    }
}