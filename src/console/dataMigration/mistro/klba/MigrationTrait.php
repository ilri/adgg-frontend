<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-04-29
 * Time: 10:55 PM
 */

namespace console\dataMigration\mistro\klba;


use yii\db\Connection;
use yii\base\InvalidConfigException;

trait MigrationTrait
{
    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX;
    }

    public static function getOrgName()
    {
        return Migrate::ORG_NAME;
    }

    public static function getDb()
    {
        return \Yii::$app->get('kenyadb');
    }
}