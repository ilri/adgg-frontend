<?php

namespace console\dataMigration\mistro\stanley;

class Farms extends \console\dataMigration\mistro\klba\Farms
{
    use MigrationTrait;

    public static function getMigrationQueryCondition()
    {
        return ['Farms_HideFlag' => 0, 'Farms_Owner' => '24180001'];
    }

    public static function getClientMigrationIdPrefix()
    {
        return Clients::getMigrationIdPrefix();
    }
}