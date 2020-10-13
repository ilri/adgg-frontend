<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-05-12
 * Time: 2:50 PM
 */

namespace backend\modules\core\models;


interface FarmMetadataInterface
{

    /**
     * @return int
     */
    public static function getDefinedMetadataType(): int;
}