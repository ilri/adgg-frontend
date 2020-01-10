<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 2:12 PM
 */

namespace backend\modules\core\models;


use common\helpers\Utils;
use yii\base\InvalidArgumentException;

class ExtendableTable
{
    //tables
    const TABLE_FARM = 2;
    const TABLE_ANIMAL_ATTRIBUTES = 3;
    const TABLE_ANIMAL_EVENTS = 4;
    const TABLE_ANIMAL_REPEATS = 5;
    const TABLE_FARM_REPEATS = 6;
    const TABLE_CLIENT_REPEATS = 7;
    const TABLE_USERS = 8;

    /**
     * @param int $intVal
     * @return string
     */
    public static function decodeTableId($intVal)
    {
        switch ($intVal) {
            case self::TABLE_FARM:
                return 'Farm Attributes';
            case self::TABLE_ANIMAL_ATTRIBUTES:
                return 'Animal Attributes';
            case self::TABLE_ANIMAL_EVENTS:
                return 'Animal Events';
            case self::TABLE_ANIMAL_REPEATS:
                return 'Animal Repeats';
            case self::TABLE_FARM_REPEATS:
                return 'Farm Repeats';
            case self::TABLE_CLIENT_REPEATS:
                return 'Client/Person Repeats';
            case self::TABLE_USERS:
                return 'Users';
            default:
                throw new InvalidArgumentException();
        }
    }

    /**
     * @param mixed $prompt
     * @return array
     */
    public static function tableOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::TABLE_FARM => static::decodeTableId(self::TABLE_FARM),
            self::TABLE_FARM_REPEATS => static::decodeTableId(self::TABLE_FARM_REPEATS),
            //self::TABLE_CLIENT => static::decodeTableId(self::TABLE_CLIENT),
            //self::TABLE_CLIENT_REPEATS => static::decodeTableId(self::TABLE_CLIENT_REPEATS),
            self::TABLE_ANIMAL_ATTRIBUTES => static::decodeTableId(self::TABLE_ANIMAL_ATTRIBUTES),
            self::TABLE_ANIMAL_REPEATS => static::decodeTableId(self::TABLE_ANIMAL_REPEATS),
            self::TABLE_ANIMAL_EVENTS => static::decodeTableId(self::TABLE_ANIMAL_EVENTS),
            self::TABLE_USERS => static::decodeTableId(self::TABLE_USERS),
        ], $prompt);
    }
}