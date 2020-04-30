<?php

namespace console\dataMigration\mistro\klba;

use backend\modules\core\models\Client;
use backend\modules\core\models\Farm;
use console\dataMigration\mistro\Helper;
use console\dataMigration\mistro\MigrationBase;
use console\dataMigration\mistro\MigrationInterface;

/**
 * This is the model class for table "farms".
 *
 * @property float $Farms_ID
 * @property string $Farms_SHNo
 * @property float|null $Farms_Owner
 * @property float|null $Farms_Share
 * @property string|null $Farms_Address
 * @property int|null $Farms_Units
 * @property string|null $Farms_EUID
 * @property string|null $Farms_Lat
 * @property string|null $Farms_Long
 * @property string|null $Farms_Location
 * @property int|null $Farms_TotArea
 * @property int|null $Farms_MilkArea
 * @property int|null $Farms_IrrArea
 * @property string|null $Farms_Flags1
 * @property string|null $Farms_Flags2
 * @property int $Farms_Upload
 * @property int $Farms_Download
 * @property string|null $Farms_Modified
 * @property string|null $Farms_ModifiedBy
 * @property int $Farms_HideFlag
 * @property int $Farms_Locked
 */
class Farms extends MigrationBase implements MigrationInterface
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%farms}}';
    }

    public static function migrateData()
    {
        $query = static::find()->andWhere(static::getMigrationQueryCondition());
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(\console\dataMigration\mistro\Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(static::getOrgName());
        $model = new Farm(['country_id' => $countryId, 'org_id' => $orgId]);
        foreach ($query->batch() as $i => $dataModels) {
            foreach ($dataModels as $dataModel) {
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->Farms_ID, static::getMigrationIdPrefix());
                $newModel->client_id = static::getClientId($dataModel->Farms_Owner);
                $newModel->code = $dataModel->Farms_SHNo;
                $newModel->farm_postal_address = $dataModel->Farms_Address;
                $newModel->latitude = $dataModel->Farms_Lat;
                $newModel->longitude = $dataModel->Farms_Long;
                $newModel->farm_location = $dataModel->Farms_Location;
                $newModel->farm_town = $dataModel->Farms_Flags1;
                $newModel->farm_country = $dataModel->Farms_Flags2;
                $newModel->farmer_name = $newModel->client->contact_person ?? null;
                $newModel->farm_type = 'LSF';
                $newModel->name = $newModel->client->name ?? null;
                if (empty($newModel->farmer_name)) {
                    $newModel->farmer_name = $newModel->code;
                }
                if (empty($newModel->name)) {
                    $newModel->name = $newModel->code;
                }
                $newModel->reg_date = $dataModel->Farms_Modified;
                if ($newModel->reg_date == '0000-00-00') {
                    $newModel->reg_date = null;
                }

                static::saveModel($newModel, $n);
                $n++;
            }
        }
    }

    /**
     * @param string $oldClientId
     * @return string|null
     * @throws \Exception
     */
    public static function getClientId($oldClientId)
    {
        $migrationId = Helper::getMigrationId($oldClientId, static::getClientMigrationIdPrefix());

        $clientId = Client::getScalar('id', ['migration_id' => $migrationId]);
        if (empty($clientId)) {
            return null;
        }
        return $clientId;
    }

    public static function getMigrationQueryCondition()
    {
        return ['Farms_HideFlag' => 0];
    }

    public static function getClientMigrationIdPrefix()
    {
        return Clients::getMigrationIdPrefix();
    }
}