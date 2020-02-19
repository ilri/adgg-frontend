<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-02-19
 * Time: 10:55 PM
 */

namespace console\dataMigration\ke\models;


interface MigrationInterface
{
    public static function migrateData();
}