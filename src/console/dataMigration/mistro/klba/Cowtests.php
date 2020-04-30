<?php

namespace console\dataMigration\mistro\klba;

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\MilkingEvent;
use console\dataMigration\mistro\Helper;
use console\dataMigration\mistro\MigrationBase;
use console\dataMigration\mistro\MigrationInterface;
use Yii;

/**
 * This is the model class for table "cowtests".
 *
 * @property string $CowTests_ID
 * @property string $CowTests_TDayID
 * @property string $CowTests_CowID
 * @property int|null $CowTests_PMYield
 * @property int|null $CowTests_AMYield
 * @property int|null $CowTests_Yield1
 * @property int|null $CowTests_FatP
 * @property int|null $CowTests_ProtP
 * @property int|null $CowTests_ICCC
 * @property int|null $CowTests_LactP
 * @property int|null $CowTests_SampleNo
 * @property int|null $CowTests_Exclude
 * @property int|null $CowTests_RecsInAve
 * @property int|null $CowTests_TestGroup
 * @property string|null $CowTests_UpdateDate
 * @property int|null $CowTests_UpdateStatus
 * @property int|null $CowTests_RejectY
 * @property int|null $CowTests_RejectF
 * @property int|null $CowTests_RejectP
 * @property int|null $CowTests_RejectL
 * @property float|null $CowTests_YIndex
 * @property float|null $CowTests_FIndex
 * @property float|null $CowTests_PIndex
 * @property float|null $CowTests_LIndex
 * @property string|null $CowTests_FirstTest
 * @property string|null $CowTests_LastTest
 * @property string|null $CowTests_NowInfected
 * @property float|null $CowTests_LactID
 * @property int $CowTests_Upload
 * @property int $CowTests_Download
 * @property string|null $CowTests_Modified
 * @property string|null $CowTests_ModifiedBy
 * @property int $CowTests_HideFlag
 * @property int $CowTests_Locked
 *
 * @property Testdays $testDay
 * @property Lacts $lactation
 */
class Cowtests extends MigrationBase implements MigrationInterface
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cowtests}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestDay()
    {
        return $this->hasOne(Testdays::class, ['TestDays_ID' => 'CowTests_TDayID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLactation()
    {
        return $this->hasOne(Lacts::class, ['Lacts_ID' => 'CowTests_LactID']);
    }

    public static function migrateData()
    {
        $condition = ['CowTests_HideFlag' => 0];
        $query = static::find()->andWhere($condition);
        $totalRecords = static::getCount($condition);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(\console\dataMigration\mistro\Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(static::getOrgName());
        $model = new MilkingEvent(['country_id' => $countryId, 'org_id' => $orgId, 'event_type' => AnimalEvent::EVENT_TYPE_MILKING, 'scenario' => MilkingEvent::SCENARIO_MISTRO_DB_UPLOAD]);
        $model->setAdditionalAttributes();
        $prefix = static::getMigrationIdPrefix();
        $className = get_class($model);
        foreach ($query->batch(3000) as $i => $dataModels) {
            if($n<360000){
                $n++;
                Yii::$app->controller->stdout($prefix.": ".$className.": Record {$n} of {$totalRecords} has been processed. Ignored...\n");
                continue;
            }
            Yii::$app->controller->stdout("Batch processing  started...\n");
            $migrationIds = [];
            $testDayIds = [];
            $oldAnimalIds = [];
            $testDayData = [];
            $animalData = [];
            $oldLactIds = [];
            $lactData = [];
            //Yii::$app->controller->stdout("Setting default configs...\n");
            foreach ($dataModels as $dataModel) {
                //migration_id must be unique
                $migrationIds[] = Helper::getMigrationId($dataModel->CowTests_ID, static::getTestDayMigrationIdPrefix());
                //query db
                $testDayIds[$dataModel->CowTests_TDayID] = $dataModel->CowTests_TDayID;
                $animalMigId = Helper::getMigrationId($dataModel->CowTests_CowID, static::getCowMigrationIdPrefix());
                $oldAnimalIds[$animalMigId] = $animalMigId;
                $oldLactId = Helper::getMigrationId($dataModel->CowTests_LactID, static::getLactMigrationIdPrefix());
                $oldLactIds[$oldLactId] = $oldLactId;
            }
            $existingMigrationIds = AnimalEvent::getColumnData(['migration_id'], ['migration_id' => $migrationIds]);
            //testDay Data
            //Yii::$app->controller->stdout("Setting testDay data...\n");
            foreach (static::getTestDaysData($testDayIds) as $testDayDatum) {
                $testDayData[$testDayDatum['TestDays_ID']] = $testDayDatum;
            }

            //animal Data
            //Yii::$app->controller->stdout("Setting animal data...\n");
            foreach (Animal::getData(['id', 'migration_id'], ['migration_id' => $oldAnimalIds]) as $animalDatum) {
                $animalData[$animalDatum['migration_id']] = $animalDatum['id'];
            }

            //lactation Data
            //Yii::$app->controller->stdout("Setting lactation data...\n");
            foreach (AnimalEvent::getData(['id', 'migration_id'], ['migration_id' => $oldLactIds, 'event_type' => AnimalEvent::EVENT_TYPE_CALVING]) as $lactDatum) {
                $lactData[$lactDatum['migration_id']] = $lactDatum['id'];
            }

            $ids = [];
            foreach ($dataModels as $dataModel) {
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->CowTests_ID, static::getTestDayMigrationIdPrefix());
                if (in_array($newModel->migration_id, $existingMigrationIds)) {
                    Yii::$app->controller->stdout($prefix . ": " . $className . ": record {$n} of {$totalRecords} with migration id: {$newModel->migration_id} already saved. Ignored\n");
                    $n++;
                    continue;
                }
                $animalMigId = Helper::getMigrationId($dataModel->CowTests_CowID, static::getCowMigrationIdPrefix());
                $newModel->animal_id = $animalData[$animalMigId] ?? null;
                $newModel->event_date = $testDayData[$dataModel->CowTests_TDayID]['TestDays_Date'] ?? null;
                if ($newModel->event_date == '0000-00-00') {
                    $newModel->event_date = null;
                }

                //'animal_id','event_date' are required
                if (empty($newModel->animal_id)) {
                    Yii::$app->controller->stdout($prefix . ": " . $className . ": Validation error on milk record {$n} of {$totalRecords}: Animal Id cannot be blank.\n");
                    $n++;
                    continue;
                }
                if (empty($newModel->event_date)) {
                    Yii::$app->controller->stdout($prefix . ": " . $className . ": Validation error on milk record {$n} of {$totalRecords}: Event date cannot be blank.\n");
                    $n++;
                    continue;
                }
                $newModel->milk_test_type = $testDayData[$dataModel->CowTests_TDayID]['TestDays_TestType'] ?? null;

                $newModel->milkmor = ((float)$dataModel->CowTests_AMYield) / 100;
                $newModel->milkeve = ((float)$dataModel->CowTests_PMYield) / 100;
                $newModel->milkday = ((float)$dataModel->CowTests_Yield1) / 100;
                $newModel->milkfat = ((float)$dataModel->CowTests_FatP) / 100;
                $newModel->milkprot = ((float)$dataModel->CowTests_ProtP) / 100;
                $newModel->milklact = ((float)$dataModel->CowTests_LactP) / 100;
                $newModel->milksmc = $dataModel->CowTests_ICCC;

                $oldLactId = Helper::getMigrationId($dataModel->CowTests_LactID, static::getLactMigrationIdPrefix());
                $newModel->lactation_id = $lactData[$oldLactId] ?? null;

                $newModel = static::saveModel($newModel, $n, $totalRecords, false);
                if (!empty($newModel->id) && !empty($newModel->lactation_id)) {
                    $ids[$newModel->animal_id . $newModel->lactation_id] = ['animal_id' => $newModel->animal_id, 'lactation_id' => $newModel->lactation_id];
                }
                $n++;
            }

            if (!empty($ids)) {
                Yii::$app->controller->stdout("Updating testday_no ...\n");
                foreach ($ids as $event) {
                    MilkingEvent::setTestDayNo($event['animal_id'], $event['lactation_id']);
                }
            }
        }
    }

    public static function getCowMigrationIdPrefix()
    {
        return Cows::getMigrationIdPrefix();
    }

    public static function getTestDayMigrationIdPrefix()
    {
        return Testdays::getMigrationIdPrefix();
    }

    public static function getLactMigrationIdPrefix()
    {
        return Lacts::getMigrationIdPrefix();
    }

    public static function getTestDaysData($testDayIds)
    {
        return Testdays::getData(['TestDays_ID', 'TestDays_Date', 'TestDays_TestType'], ['TestDays_ID' => $testDayIds]);
    }
}