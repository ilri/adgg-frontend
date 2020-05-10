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
    const TABLE_CLIENTS = 1;
    const TABLE_FARM = 2;
    const TABLE_ANIMAL_ATTRIBUTES = 3;
    const TABLE_ANIMAL_EVENTS = 4;
    const TABLE_ANIMAL_REPEATS = 5;
    const TABLE_FARM_REPEATS = 6;
    const TABLE_HERDS = 7;
    const TABLE_USERS = 8;
    const TABLE_FARM_METADATA = 9;

    /**
     * @param int $intVal
     * @return string
     */
    public static function decodeTableId($intVal)
    {
        switch ($intVal) {
            case self::TABLE_CLIENTS:
                return 'Client';
            case self::TABLE_FARM:
                return 'Farm';
            case self::TABLE_FARM_METADATA:
                return 'Farm Metadata';
            case self::TABLE_ANIMAL_ATTRIBUTES:
                return 'Animal';
            case self::TABLE_ANIMAL_EVENTS:
                return 'Animal Events';
            case self::TABLE_ANIMAL_REPEATS:
                return 'Animal Repeats';
            case self::TABLE_FARM_REPEATS:
                return 'Farm Repeats';
            case self::TABLE_HERDS:
                return 'Herds';
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
            self::TABLE_FARM_METADATA => static::decodeTableId(self::TABLE_FARM_METADATA),
            self::TABLE_HERDS => static::decodeTableId(self::TABLE_HERDS),
            self::TABLE_ANIMAL_ATTRIBUTES => static::decodeTableId(self::TABLE_ANIMAL_ATTRIBUTES),
            self::TABLE_ANIMAL_EVENTS => static::decodeTableId(self::TABLE_ANIMAL_EVENTS),
            self::TABLE_USERS => static::decodeTableId(self::TABLE_USERS),
            self::TABLE_CLIENTS => static::decodeTableId(self::TABLE_CLIENTS),
        ], $prompt);
    }
}