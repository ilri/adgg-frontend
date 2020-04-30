<?php

namespace console\dataMigration\mistro\klba;

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CalvingEvent;
use console\dataMigration\mistro\Helper;
use console\dataMigration\mistro\MigrationBase;
use console\dataMigration\mistro\MigrationInterface;
use Yii;

/**
 * This is the model class for table "lacts".
 *
 * @property float $Lacts_ID
 * @property string $Lacts_CowID
 * @property string $Lacts_InitDate
 * @property int $Lacts_InitCode
 * @property int|null $Lacts_LactNo
 * @property float|null $Lacts_CalvingHerd
 * @property string|null $Lacts_TermDate
 * @property int|null $Lacts_TermCode
 * @property int|null $Lacts_Hormone
 * @property int|null $Lacts_TFreq
 * @property string|null $Lacts_Accept
 * @property string|null $Lacts_StatID
 * @property int $Lacts_Upload
 * @property int $Lacts_Download
 * @property int|null $Lacts_Flag
 * @property string|null $Lacts_Modified
 * @property string|null $Lacts_ModifiedBy
 * @property int $Lacts_HideFlag
 * @property int $Lacts_Locked
 */
class Lacts extends MigrationBase implements MigrationInterface
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lacts}}';
    }

    public static function migrateData()
    {
        $condition = ['Lacts_HideFlag' => 0];
        $query = static::find()->andWhere($condition);
        $totalRecords = static::getCount($condition);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(\console\dataMigration\mistro\Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(static::getOrgName());
        $model = new CalvingEvent(['country_id' => $countryId, 'org_id' => $orgId, 'event_type' => AnimalEvent::EVENT_TYPE_CALVING, 'scenario' => CalvingEvent::SCENARIO_MISTRO_DB_UPLOAD]);
        $model->setAdditionalAttributes();
        foreach ($query->batch(1000) as $i => $dataModels) {
            Yii::$app->controller->stdout("Batch processing  started...\n");
            $migrationIds = [];
            $oldAnimalIds = [];
            $animalData = [];
            foreach ($dataModels as $dataModel) {
                //migration_id must be unique
                $migrationIds[] = Helper::getMigrationId($dataModel->Lacts_ID, static::getMigrationIdPrefix());
                $animalMigId = Helper::getMigrationId($dataModel->Lacts_CowID, static::getCowMigrationIdPrefix());
                $oldAnimalIds[$animalMigId] = $animalMigId;
            }
            $existingMigrationIds = AnimalEvent::getColumnData(['migration_id'], ['migration_id' => $migrationIds]);
            //animal Data
            //Yii::$app->controller->stdout("Setting animal data...\n");
            foreach (Animal::getData(['id', 'migration_id'], ['migration_id' => $oldAnimalIds]) as $animalDatum) {
                $animalData[$animalDatum['migration_id']] = $animalDatum['id'];
            }

            foreach ($dataModels as $dataModel) {
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->Lacts_ID, static::getMigrationIdPrefix());
                if (in_array($newModel->migration_id, $existingMigrationIds)) {
                    Yii::$app->controller->stdout("Calving record {$n} with migration id: {$newModel->migration_id} already saved. Ignored\n");
                    $n++;
                    continue;
                }

                $animalMigId = Helper::getMigrationId($dataModel->Lacts_CowID, static::getCowMigrationIdPrefix());
                $newModel->animal_id = $animalData[$animalMigId] ?? null;

                $newModel->event_date = $dataModel->Lacts_InitDate;
                if ($newModel->event_date == '0000-00-00') {
                    $newModel->event_date = null;
                }


                //'animal_id','event_date' are required
                if (empty($newModel->animal_id)) {
                    Yii::$app->controller->stdout("Validation error on calving record {$n}: Animal Id cannot be blank.\n");
                    $n++;
                    continue;
                }
                if (empty($newModel->event_date)) {
                    Yii::$app->controller->stdout("Validation error on calving record {$n}: Event date cannot be blank.\n");
                    $n++;
                    continue;
                }

                if ($dataModel->Lacts_InitCode == 0) {
                    $newModel->calvtype = 1;
                    $newModel->calving_method = 1;
                } elseif ($dataModel->Lacts_InitCode == 1 || $dataModel->Lacts_InitCode == 3) {
                    $newModel->calvtype = 1;
                    $newModel->calving_method = 2;
                } elseif ($dataModel->Lacts_InitCode == 1 || $dataModel->Lacts_InitCode == 3) {
                    $newModel->calvtype = 4;
                }
                $newModel->calving_dry_date = $dataModel->Lacts_TermDate;
                static::saveModel($newModel, $n, $totalRecords, false);
                $n++;
            }
        }
    }

    /**
     * @param $oldAnimalId
     * @return string|null
     * @throws \Exception
     */
    public static function getAnimalId($oldAnimalId)
    {
        $migrationId = Helper::getMigrationId($oldAnimalId, static::getCowMigrationIdPrefix());
        $animalId = Animal::getScalar('id', ['migration_id' => $migrationId]);
        if (empty($animalId)) {
            return null;
        }
        return $animalId;
    }

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'CALVING_EVENT_';
    }

    public static function getCowMigrationIdPrefix()
    {
        return Cows::getMigrationIdPrefix();
    }

}