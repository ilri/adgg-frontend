<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-05-13
 * Time: 2:14 PM
 */

namespace backend\modules\core\models;


class FarmMetadataHealth extends FarmMetadataModel
{
    public static function getDefineMetadataType(): int
    {
        return self::TYPE_HEALTH_SERVICES_METADATA;
    }
}