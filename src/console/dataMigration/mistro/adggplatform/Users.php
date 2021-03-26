<?php


namespace console\dataMigration\mistro\adggplatform;

use console\dataMigration\mistro\MigrationBase;
use console\dataMigration\mistro\MigrationInterface;


class Users extends MigrationBase implements MigrationInterface
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%staff_list`}}';
    }


    public static function migrateData()
    {
        // TODO: Implement migrateData() method.
    }

    public static function getMigrationIdPrefix()
    {
        // TODO: Implement getMigrationIdPrefix() method.
    }
}