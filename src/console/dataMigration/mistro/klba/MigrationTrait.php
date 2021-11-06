<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-04-29
 * Time: 10:55 PM
 */

namespace console\dataMigration\mistro\klba;


use \Yii;

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

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('mistroDbKlba');
    }
}