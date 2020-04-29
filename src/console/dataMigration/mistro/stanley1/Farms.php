<?php

namespace console\dataMigration\mistro\stanley1;

use backend\modules\core\models\Client;
use console\dataMigration\mistro\Helper;

class Farms extends \console\dataMigration\mistro\klba\Farms
{
    use MigrationTrait;

    /**
     * @param string $oldClientId
     * @return string|null
     * @throws \Exception
     */
    public static function getClientId($oldClientId)
    {
        $migrationId = Helper::getMigrationId($oldClientId, Clients::getMigrationIdPrefix());

        $clientId = Client::getScalar('id', ['migration_id' => $migrationId]);
        if (empty($clientId)) {
            return null;
        }
        return $clientId;
    }

    public static function getMigrationQueryCondition()
    {
        return ['Farms_HideFlag' => 0, 'Farms_Owner' => '24180001'];
    }
}