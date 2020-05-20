<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-05-20
 * Time: 1:33 PM
 */

namespace backend\modules\core\models;


class FarmMetadataBreedingBulls extends FarmMetadata
{

    public static function getDefineMetadataType(): int
    {
        return 4;//this method will be removed
    }
}