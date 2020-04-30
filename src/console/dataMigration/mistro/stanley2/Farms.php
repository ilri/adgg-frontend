<?php

namespace console\dataMigration\mistro\stanley2;

class Farms extends \console\dataMigration\mistro\klba\Farms
{
    use MigrationTrait;

    public static function getMigrationQueryCondition()
    {
        return ['Farms_HideFlag' => 0];
    }

    public static function getClientMigrationIdPrefix()
    {
        return Clients::getMigrationIdPrefix();
    }
}