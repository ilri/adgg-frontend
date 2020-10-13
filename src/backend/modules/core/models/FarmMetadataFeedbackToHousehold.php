<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-08-27
 * Time: 5:03 PM
 */

namespace backend\modules\core\models;


class FarmMetadataFeedbackToHousehold extends FarmMetadata implements FarmMetadataInterface
{
    public static function getDefinedMetadataType(): int
    {
        return self::TYPE_FEEDBACK_TO_HOUSEHOLD;
    }
}