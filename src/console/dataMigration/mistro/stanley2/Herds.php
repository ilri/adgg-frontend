<?php

namespace console\dataMigration\mistro\stanley2;

class Herds extends \console\dataMigration\mistro\klba\Herds
{
    use MigrationTrait;

    public static function getFarmMigrationIdPrefix()
    {
        return Farms::getMigrationIdPrefix();
    }
}