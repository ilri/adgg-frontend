<?php

namespace console\dataMigration\mistro\stanley1;

class Cows extends \console\dataMigration\mistro\klba\Cows
{
    use MigrationTrait;

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'COWS_';
    }

    public static function getBullMigrationIdPrefix()
    {
        return Bulls::getMigrationIdPrefix();
    }

    public static function getHerdMigrationIdPrefix()
    {
        return Herds::getMigrationIdPrefix();
    }
}