<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-07-09
 * Time: 5:25 PM
 */

namespace backend\modules\core\models;


class FarmMetadataTechnologyMobilization extends FarmMetadata implements FarmMetadataInterface
{

    public static function getDefineMetadataType(): int
    {
        return self::TYPE_TECHNOLOGY_MOBILIZATION;
    }
}