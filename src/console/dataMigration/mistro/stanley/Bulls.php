<?php

namespace console\dataMigration\mistro\stanley;

class Bulls extends \console\dataMigration\mistro\klba\Bulls
{
    use MigrationTrait;

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'BULLS_';
    }

    public static function getCowMigrationIdPrefix()
    {
        return Cows::getMigrationIdPrefix();
    }

}
