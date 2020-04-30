<?php

namespace console\dataMigration\mistro\stanley1;

class Lacts extends \console\dataMigration\mistro\klba\Lacts
{
    use MigrationTrait;

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'CALVING_EVENT_';
    }

    public static function getCowMigrationIdPrefix()
    {
        return Cows::getMigrationIdPrefix();
    }

}