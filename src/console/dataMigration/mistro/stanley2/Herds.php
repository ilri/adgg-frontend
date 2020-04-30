<?php

namespace console\dataMigration\mistro\stanley2;

use backend\modules\core\models\Farm;
use console\dataMigration\mistro\Helper;

class Herds extends \console\dataMigration\mistro\klba\Herds
{
    use MigrationTrait;

    public static function getFarmMigrationIdPrefix()
    {
        return Farms::getMigrationIdPrefix();
    }

    /**
     * @param string $oldFarmId
     * @return string|null
     * @throws \Exception
     */
    public static function getFarmId($oldFarmId)
    {
        $oldFarmId = '24180001';
        $migrationId = Helper::getMigrationId($oldFarmId, \console\dataMigration\mistro\stanley1\Farms::getMigrationIdPrefix());

        $farmId = Farm::getScalar('id', ['migration_id' => $migrationId]);
        if (empty($farmId)) {
            return null;
        }
        return $farmId;
    }
}