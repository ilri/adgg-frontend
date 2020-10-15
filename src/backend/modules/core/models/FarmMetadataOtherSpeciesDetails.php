<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-11
 * Time: 3:34 PM
 */

namespace backend\modules\core\models;


class FarmMetadataOtherSpeciesDetails extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_OTHER_SPECIES_DETAILS;
    }
}