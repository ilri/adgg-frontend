<?php

namespace console\dataMigration\mistro\stanley2;

use backend\modules\core\models\AnimalHerd;

class Bulls extends \console\dataMigration\mistro\klba\Bulls
{
    use MigrationTrait;

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'BULLS_';
    }

    public static function getCowMigrationIdPrefix()
    {
        return Cows::getMigrationIdPrefix();
    }

    /**
     * @param int $oldHerdId
     * @return array|AnimalHerd|\yii\db\ActiveRecord|null
     */
    public static function getHerd($oldHerdId)
    {
        return Cows::getHerd($oldHerdId);
    }

}
