<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-04-29
 * Time: 10:24 PM
 */

namespace console\dataMigration\mistro\adggplatform;


use console\dataMigration\mistro\MigrateInterface;

class Migrate implements MigrateInterface
{
    const ORG_NAME = 'ADGG';
    const DATA_SOURCE_PREFIX = 'ADGG_';

    public static function run()
    {
        Clients::migrateData();
        Farms::migrateData();
        Herds::migrateData();
        Cows::migrateData();
        Cowtests::migrateData();
        Cows::updateSiresAndDams();
    }
}