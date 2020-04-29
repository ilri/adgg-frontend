<?php

namespace console\dataMigration\mistro\klba;

use backend\modules\core\models\Client;
use console\dataMigration\mistro\Helper;
use console\dataMigration\mistro\MigrationBase;
use console\dataMigration\mistro\MigrationInterface;

/**
 * This is the model class for table "clients".
 *
 * @property float $Clients_ID
 * @property string $Clients_PersonName
 * @property string $Clients_BusName
 * @property string|null $Clients_Greeting
 * @property string|null $Clients_Address1
 * @property string|null $Clients_Address2
 * @property string|null $Clients_Address3
 * @property int|null $Clients_Postcode
 * @property string|null $Clients_SHNo
 * @property string|null $Clients_Phone
 * @property string|null $Clients_Mobile
 * @property string|null $Clients_Fax
 * @property string|null $Clients_Email
 * @property string|null $Clients_FactDeduct
 * @property string|null $Clients_FactAcctNo
 * @property string|null $Clients_TaxNo
 * @property string|null $Clients_AccountKey
 * @property string|null $Clients_AccountGroup
 * @property string $Clients_Suspend
 * @property string|null $Clients_Flags1
 * @property string|null $Clients_Flags2
 * @property float|null $Clients_Branch
 * @property string|null $Clients_Remark
 * @property float|null $Clients_ShareHolder
 * @property int $Clients_Upload
 * @property int $Clients_Download
 * @property string|null $Clients_Modified
 * @property string|null $Clients_ModifiedBy
 * @property int $Clients_HideFlag
 * @property int $Clients_Locked
 */
class Clients extends MigrationBase implements MigrationInterface
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%clients}}';
    }

    public static function migrateData()
    {
        $query = static::find()->andWhere(['Clients_HideFlag' => 0]);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(\console\dataMigration\mistro\Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(static::getOrgName());
        $model = new Client(['country_id' => $countryId, 'org_id' => $orgId]);
        foreach ($query->batch(1000) as $i => $dataModels) {
            foreach ($dataModels as $dataModel) {
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->Clients_ID, static::getMigrationIdPrefix());
                $newModel->name = !empty(trim($dataModel->Clients_BusName)) ? $dataModel->Clients_BusName : $dataModel->Clients_PersonName;
                $newModel->contact_person = $dataModel->Clients_PersonName;
                $newModel->postal_address = $dataModel->Clients_Address1;
                $newModel->town = $dataModel->Clients_Address2;
                $newModel->client_country = $dataModel->Clients_Address3;
                $newModel->phone1 = $dataModel->Clients_Mobile;
                $newModel->phone2 = $dataModel->Clients_Phone;
                $newModel->email = $dataModel->Clients_Email;
                $newModel->county = $dataModel->Clients_Branch;
                $newModel->remarks = $dataModel->Clients_Remark;
                static::saveModel($newModel, $n);
                $n++;
            }
        }
    }
}
