<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-11
 * Time: 3:27 PM
 */

namespace backend\modules\core\models;


class FarmMetadataLivestockDetails extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefineMetadataType(): int
    {
        return self::TYPE_LIVESTOCK_DETAILS;
    }
}