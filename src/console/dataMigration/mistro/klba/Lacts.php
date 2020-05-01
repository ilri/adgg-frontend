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
        $className = get_class($model);
        $prefix = static::getMigrationIdPrefix();
        foreach ($query->batch(3000) as $i => $dataModels) {
            Yii::$app->controller->stdout("Batch processing  started...\n");
            $migrationIds = [];
            $animalData = [];
            $cowIds = [];
            foreach ($dataModels as $dataModel) {
                //migration_id must be unique
                $migrationIds[] = Helper::getMigrationId($dataModel->Lacts_ID, static::getMigrationIdPrefix());
                $cowIds[] = $dataModel->Lacts_CowID;
            }
            $cows = static::getCowsData($cowIds);
            $animalTagIds = [];
            foreach ($cows as $cow) {
                $animalTagIds[$cow['Cows_ID']] = $cow['Cows_HIONo'];
            }
            $existingMigrationIds = AnimalEvent::getColumnData(['migration_id'], ['migration_id' => $migrationIds]);
            //animal Data
            //Yii::$app->controller->stdout("Setting animal data...\n");
            foreach (Animal::getData(['id', 'tag_id'], ['tag_id' => $animalTagIds]) as $animalDatum) {
                $animalData[$animalDatum['tag_id']] = $animalDatum['id'];
            }

            $ids = [];
            foreach ($dataModels as $dataModel) {
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->Lacts_ID, static::getMigrationIdPrefix());
                if (in_array($newModel->migration_id, $existingMigrationIds)) {
                    Yii::$app->controller->stdout("Calving record {$n} with migration id: {$newModel->migration_id} already saved. Ignored\n");
                    $n++;
                    continue;
                }

                $animalTagId = $animalTagIds[$dataModel->Lacts_CowID] ?? null;
                $newModel->animal_id = $animalData[$animalTagId] ?? null;
                $newModel->event_date = $dataModel->Lacts_InitDate;
                if ($newModel->event_date == '0000-00-00') {
                    $newModel->event_date = null;
                }


                //'animal_id','event_date' are required
                if (empty($newModel->animal_id)) {
                    Yii::$app->controller->stdout($prefix . ": " . $className . "Validation error on calving record {$n} of {$totalRecords}: Animal Id cannot be blank.\n");
                    $n++;
                    continue;
                }
                if (empty($newModel->event_date)) {
                    Yii::$app->controller->stdout($prefix . ": " . $className . "Validation error on calving record {$n} of {$totalRecords}: Event date cannot be blank.\n");
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
                $newModel = static::saveModel($newModel, $n, $totalRecords, false);
                if (!empty($newModel->id)) {
                    $ids[$newModel->animal_id] = ['animal_id' => $newModel->animal_id];
                }
                $n++;
            }

            if (!empty($ids)) {
                Yii::$app->controller->stdout("Updating lactation_number ...\n");
                foreach ($ids as $event) {
                    AnimalEvent::setLactationNumber($event['animal_id']);
                }
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

    public static function getCowsData($cowIds)
    {
        return Cows::getData(['Cows_HIONo', 'Cows_ID'], ['Cows_ID' => $cowIds]);
    }

}