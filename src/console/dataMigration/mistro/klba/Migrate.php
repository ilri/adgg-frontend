<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-04-29
 * Time: 10:24 PM
 */

namespace console\dataMigration\mistro\klba;


use console\dataMigration\mistro\MigrateInterface;

class Migrate implements MigrateInterface
{
    const ORG_NAME = 'KLBA';
    const DATA_SOURCE_PREFIX = 'KLBA_';

    public static function run()
    {
        Clients::migrateData();
        Farms::migrateData();
        Herds::migrateData();
        Cows::migrateData();
        Bulls::migrateData();
        Lacts::migrateData();
        Cowtests::migrateData();
        Cows::updateSiresAndDams();
    }
}