<?php

namespace console\dataMigration\mistro\stanley2;

use backend\modules\core\models\AnimalHerd;
use console\dataMigration\mistro\Helper;

class Cows extends \console\dataMigration\mistro\klba\Cows
{
    use MigrationTrait;

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'COWS_';
    }

    public static function getBullMigrationIdPrefix()
    {
        return Bulls::getMigrationIdPrefix();
    }

    public static function getHerdMigrationIdPrefix()
    {
        return Herds::getMigrationIdPrefix();
    }

    /**
     * @param int $oldHerdId
     * @return array|AnimalHerd|\yii\db\ActiveRecord|null
     */
    public static function getHerd($oldHerdId)
    {
        $oldHerdId = '24180001';
        $migrationId = Helper::getMigrationId($oldHerdId, \console\dataMigration\mistro\stanley1\Herds::getMigrationIdPrefix());
        return AnimalHerd::find()->andWhere(['migration_id' => $migrationId])->one();
    }
}