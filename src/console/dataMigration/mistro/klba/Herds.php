<?php

namespace console\dataMigration\mistro\klba;

use backend\modules\core\models\AnimalHerd;
use backend\modules\core\models\Farm;
use console\dataMigration\mistro\Helper;
use console\dataMigration\mistro\MigrationBase;
use console\dataMigration\mistro\MigrationInterface;

/**
 * This is the model class for table "herds".
 *
 * @property float $Herds_ID
 * @property string $Herds_Name
 * @property int|null $Herds_Species
 * @property float|null $Herds_Farm
 * @property float|null $Herds_Owner
 * @property string|null $Herds_ADHISID
 * @property string|null $Herds_StartDate
 * @property string|null $Herds_StopDate
 * @property int|null $Herds_CentreID
 * @property int|null $Herds_SubCentre
 * @property string|null $Herds_ADHISExclude
 * @property int|null $Herds_HistRecorded
 * @property string|null $Herds_PTHerd
 * @property string|null $Herds_UseName
 * @property string|null $Herds_QuickUpd
 * @property string|null $Herds_MatingCur
 * @property string|null $Herds_UpdateCur
 * @property string|null $Herds_ImportNLIS
 * @property string|null $Herds_UseOldPIs
 * @property string|null $Herds_AvePIto100
 * @property string|null $Herds_SeasonBreak
 * @property string|null $Herds_LastTest
 * @property string|null $Herds_LastARep
 * @property string|null $Herds_LastCCR
 * @property string|null $Herds_LRC
 * @property string|null $Herds_Flags1
 * @property string|null $Herds_Flags2
 * @property string|null $Herds_UseGroups
 * @property int|null $Herds_PI2
 * @property int|null $Herds_PI3
 * @property int|null $Herds_PI4
 * @property int|null $Herds_PI5
 * @property int $Herds_Upload
 * @property int $Herds_Download
 * @property int|null $Herds_SiresRec
 * @property int|null $Herds_MGSRec
 * @property int|null $Herds_DamsRec
 * @property int|null $Herds_DOBRec
 * @property int|null $Herds_EIDRec
 * @property int|null $Herds_NLISRec
 * @property string|null $Herds_UpdateMsgs
 * @property string|null $Herds_ImportMsgs
 * @property string|null $Herds_TestProcMsgs
 * @property int|null $Herds_MRContID
 * @property string|null $Herds_Modified
 * @property string|null $Herds_ModifiedBy
 * @property int $Herds_HideFlag
 * @property int $Herds_Locked
 */
class Herds extends MigrationBase implements MigrationInterface
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%herds}}';
    }

    public static function migrateData()
    {
        $condition = ['Herds_HideFlag' => 0];
        $query = static::find()->andWhere($condition);
        $totalRecords = static::getCount($condition);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(\console\dataMigration\mistro\Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(static::getOrgName());
        $model = new AnimalHerd(['country_id' => $countryId, 'org_id' => $orgId]);
        foreach ($query->batch(1000) as $i => $dataModels) {
            foreach ($dataModels as $dataModel) {
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->Herds_ID, static::getMigrationIdPrefix());
                $newModel->farm_id = static::getFarmId($dataModel->Herds_Farm);
                $newModel->regdate = $dataModel->Herds_StartDate;
                $newModel->exit_date = $dataModel->Herds_StopDate;
                $newModel->centre_id = $dataModel->Herds_CentreID;
                $newModel->name = $dataModel->Herds_Name;
                static::saveModel($newModel, $n, $totalRecords);
                $n++;
            }
        }
    }

    /**
     * @param string $oldFarmId
     * @return string|null
     * @throws \Exception
     */
    public static function getFarmId($oldFarmId)
    {
        $migrationId = Helper::getMigrationId($oldFarmId, static::getFarmMigrationIdPrefix());

        $farmId = Farm::getScalar('id', ['migration_id' => $migrationId]);
        if (empty($farmId)) {
            return null;
        }
        return $farmId;
    }

    public static function getFarmMigrationIdPrefix()
    {
        return Farms::getMigrationIdPrefix();
    }
}