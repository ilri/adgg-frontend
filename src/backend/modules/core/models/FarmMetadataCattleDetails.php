<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-09-11
 * Time: 4:06 PM
 */

namespace backend\modules\core\models;


class FarmMetadataCattleDetails extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefineMetadataType(): int
    {
        return self::TYPE_CATTLE_DETAILS;
    }
}