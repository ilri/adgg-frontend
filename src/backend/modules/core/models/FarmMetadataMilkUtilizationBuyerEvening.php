<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-08-27
 * Time: 3:05 PM
 */

namespace backend\modules\core\models;


class FarmMetadataMilkUtilizationBuyerEvening extends FarmMetadata
{
    public static function getDefineMetadataType(): int
    {
        return self::TYPE_MILK_UTILIZATION_BUYER_EVENING;
    }
}