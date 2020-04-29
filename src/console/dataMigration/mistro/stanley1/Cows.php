<?php

namespace console\dataMigration\mistro\stanley1;

use backend\modules\core\models\AnimalHerd;
use console\dataMigration\mistro\Helper;

class Cows extends \console\dataMigration\mistro\klba\Cows
{
    use MigrationTrait;

    /**
     * @param int $oldHerdId
     * @return array|AnimalHerd|\yii\db\ActiveRecord|null
     */
    public static function getHerd($oldHerdId)
    {
        $migrationId = Helper::getMigrationId($oldHerdId, Herds::getMigrationIdPrefix());
        return AnimalHerd::find()->andWhere(['migration_id' => $migrationId])->one();
    }

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'COWS_';
    }

    public static function getBullMigrationIdPrefix()
    {
        return Bulls::getMigrationIdPrefix();
    }
}