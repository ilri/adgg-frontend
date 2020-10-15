<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-11
 * Time: 6:32 PM
 */

namespace backend\modules\core\models;


class FarmMetadataFarmStructureDetails extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_FARM_STRUCTURE_DETAILS;
    }
}