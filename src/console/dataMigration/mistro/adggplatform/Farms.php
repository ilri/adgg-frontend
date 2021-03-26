<?php

namespace console\dataMigration\mistro\adggplatform;

use backend\modules\core\models\Client;
use backend\modules\core\models\Farm;
use backend\modules\core\models\FarmMetadataHouseholdMembers;
use console\dataMigration\mistro\Helper;
use console\dataMigration\mistro\MigrationBase;
use console\dataMigration\mistro\MigrationInterface;

/**
 * This is the model class for table "farms".
 *
 * @property  int |NULL $form_id
 * @property  int |NULL $farmer_general_rowid
 * @property  int  $farmer_farmtype
 * @property  int |NULL $farmer_code
 * @property  int  $farmer_platformid
 * @property  string |NULL $farmer_firstname
 * @property  string |NULL $farmer_othnames
 * @property  string |NULL $farmer_uniqueid
 * @property  string |NULL $farmer_mobile
 * @property  int |NULL $farmer_gender
 * @property  int |NULL $farmer_age
 * @property  int |NULL $farmer_cooperativeaffiliated
 * @property  int |NULL $farmer_cooperative
 * @property  string |NULL $farmer_cooperativeother
 * @property  int |NULL $farmer_hhhead
 * @property  int |NULL $farmer_rltshiphhoth
 * @property  string |NULL $farmer_hhhname
 * @property  string |NULL $farmer_hhhmobile
 * @property  int |NULL $farmer_hhhgender
 * @property  int |NULL $farmer_hhhage
 * @property  string |NULL $farmer_gpslocation
 * @property  string|NULL $rowuuid
 *

 */
class Farms extends MigrationBase implements MigrationInterface
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%farmer_general}}';
    }

    public static function migrateData()
    {
        $condition = static::getMigrationQueryCondition();
        $query = static::find()->andWhere($condition);
        $totalRecords = static::getCount($condition);
        /* @var $dataModels $this[] */
        $n = 1;
//      $countryId = Helper::getCountryId(\console\dataMigration\mistro\Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(static::getOrgName());
        $model = new Farm(['org_id' => $orgId]);
        $model->setAdditionalAttributes();
        $farmmetadatamodel = new FarmMetadataHouseholdMembers();
        $farmmetadatamodel->setAdditionalAttributes();
        $prefix = static::getMigrationIdPrefix();
        $className = get_class($model);
        foreach ($query->batch(1000) as $i => $dataModels) {
            if ($n < 360000) {
                $n += 1000;
                Yii::$app->controller->stdout($prefix . ": " . $className . ": Record {$n} of {$totalRecords} has been processed. Ignored...\n");
                continue;
            }
            Yii::$app->controller->stdout("Batch processing  started...\n");
            foreach ($dataModels as $dataModel) {
                $newModel = clone $model;
                $metadatamodel = clone $farmmetadatamodel;
                //migration_id must be unique
                $newModel->migration_id = Helper::getMigrationId($dataModel->form_id, static::getMigrationIdPrefix());
                $newModel->client_id = static::getClientId($dataModel->farmer_code);
                $newModel->latlng = $dataModel->farmer_gpslocation;
                $newModel->phone = $dataModel->farmer_mobile;
                $newModel->farm_type = $dataModel->farmer_farmtype;
                $newModel->gender_code = $dataModel->farmer_gender;
                $metadatamodel->hhh_name = $dataModel->farmer_hhhname;
                $metadatamodel->hhh_gender = $dataModel->farmer_hhhname;
                $metadatamodel->hhh_age = $dataModel->farmer_hhhname;
                $metadatamodel->hhmember_rltshiphhh = $dataModel->farmer_rltshiphhoth;
                static::saveModel($newModel, $n,$totalRecords);
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