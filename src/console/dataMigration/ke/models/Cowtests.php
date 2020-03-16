<?php

namespace console\dataMigration\ke\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\MilkingEvent;
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
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cowtests}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('mistroKeDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CowTests_ID', 'CowTests_TDayID', 'CowTests_CowID'], 'required'],
            [['CowTests_PMYield', 'CowTests_AMYield', 'CowTests_Yield1', 'CowTests_FatP', 'CowTests_ProtP', 'CowTests_ICCC', 'CowTests_LactP', 'CowTests_SampleNo', 'CowTests_Exclude', 'CowTests_RecsInAve', 'CowTests_TestGroup', 'CowTests_UpdateStatus', 'CowTests_RejectY', 'CowTests_RejectF', 'CowTests_RejectP', 'CowTests_RejectL', 'CowTests_Upload', 'CowTests_Download', 'CowTests_HideFlag', 'CowTests_Locked'], 'integer'],
            [['CowTests_UpdateDate', 'CowTests_Modified'], 'safe'],
            [['CowTests_YIndex', 'CowTests_FIndex', 'CowTests_PIndex', 'CowTests_LIndex', 'CowTests_LactID'], 'number'],
            [['CowTests_ID'], 'string', 'max' => 27],
            [['CowTests_TDayID'], 'string', 'max' => 16],
            [['CowTests_CowID'], 'string', 'max' => 11],
            [['CowTests_FirstTest', 'CowTests_LastTest', 'CowTests_NowInfected'], 'string', 'max' => 1],
            [['CowTests_ModifiedBy'], 'string', 'max' => 10],
            [['CowTests_CowID', 'CowTests_TDayID', 'CowTests_HideFlag'], 'unique', 'targetAttribute' => ['CowTests_CowID', 'CowTests_TDayID', 'CowTests_HideFlag']],
            [['CowTests_TDayID', 'CowTests_CowID', 'CowTests_HideFlag'], 'unique', 'targetAttribute' => ['CowTests_TDayID', 'CowTests_CowID', 'CowTests_HideFlag']],
            [['CowTests_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CowTests_ID' => 'Cow Tests ID',
            'CowTests_TDayID' => 'Cow Tests T Day ID',
            'CowTests_CowID' => 'Cow Tests Cow ID',
            'CowTests_PMYield' => 'Cow Tests Pm Yield',
            'CowTests_AMYield' => 'Cow Tests Am Yield',
            'CowTests_Yield1' => 'Cow Tests Yield1',
            'CowTests_FatP' => 'Cow Tests Fat P',
            'CowTests_ProtP' => 'Cow Tests Prot P',
            'CowTests_ICCC' => 'Cow Tests Iccc',
            'CowTests_LactP' => 'Cow Tests Lact P',
            'CowTests_SampleNo' => 'Cow Tests Sample No',
            'CowTests_Exclude' => 'Cow Tests Exclude',
            'CowTests_RecsInAve' => 'Cow Tests Recs In Ave',
            'CowTests_TestGroup' => 'Cow Tests Test Group',
            'CowTests_UpdateDate' => 'Cow Tests Update Date',
            'CowTests_UpdateStatus' => 'Cow Tests Update Status',
            'CowTests_RejectY' => 'Cow Tests Reject Y',
            'CowTests_RejectF' => 'Cow Tests Reject F',
            'CowTests_RejectP' => 'Cow Tests Reject P',
            'CowTests_RejectL' => 'Cow Tests Reject L',
            'CowTests_YIndex' => 'Cow Tests Y Index',
            'CowTests_FIndex' => 'Cow Tests F Index',
            'CowTests_PIndex' => 'Cow Tests P Index',
            'CowTests_LIndex' => 'Cow Tests L Index',
            'CowTests_FirstTest' => 'Cow Tests First Test',
            'CowTests_LastTest' => 'Cow Tests Last Test',
            'CowTests_NowInfected' => 'Cow Tests Now Infected',
            'CowTests_LactID' => 'Cow Tests Lact ID',
            'CowTests_Upload' => 'Cow Tests Upload',
            'CowTests_Download' => 'Cow Tests Download',
            'CowTests_Modified' => 'Cow Tests Modified',
            'CowTests_ModifiedBy' => 'Cow Tests Modified By',
            'CowTests_HideFlag' => 'Cow Tests Hide Flag',
            'CowTests_Locked' => 'Cow Tests Locked',
        ];
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
        $query = static::find()->andWhere(['CowTests_HideFlag' => 0]);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(Constants::KLBA_ORG_NAME);
        $model = new MilkingEvent(['country_id' => $countryId, 'org_id' => $orgId, 'event_type' => AnimalEvent::EVENT_TYPE_MILKING, 'scenario' => MilkingEvent::SCENARIO_KLBA_UPLOAD]);
        $model->setAdditionalAttributes();
        foreach ($query->batch(1000) as $i => $dataModels) {
            Yii::$app->controller->stdout("Batch processing  started...\n");
            $migrationIds = [];
            $oldIds = [];
            $testDayIds = [];
            $oldAnimalIds = [];
            $testDayData = [];
            $animalData = [];
            $oldLactIds = [];
            $lactData = [];
            //Yii::$app->controller->stdout("Setting default configs...\n");
            foreach ($dataModels as $dataModel) {
                //migration_id must be unique
                $migrationIds[] = Helper::getMigrationId($dataModel->CowTests_ID, Testdays::MIGRATION_ID_PREFIX);
                //query db
                $oldIds[] = $dataModel->CowTests_ID;
                $testDayIds[$dataModel->CowTests_TDayID] = $dataModel->CowTests_TDayID;
                $animalMigId = Helper::getMigrationId($dataModel->CowTests_CowID, Cows::MIGRATION_ID_PREFIX);
                $oldAnimalIds[$animalMigId] = $animalMigId;
                $oldLactId = Helper::getMigrationId($dataModel->CowTests_LactID, Lacts::MIGRATION_ID_PREFIX);
                $oldLactIds[$oldLactId] = $oldLactId;
            }
            $existingMigrationIds = AnimalEvent::getColumnData(['migration_id'], ['migration_id' => $migrationIds]);
            //testDay Data
            //Yii::$app->controller->stdout("Setting testDay data...\n");
            foreach (Testdays::getData(['TestDays_ID', 'TestDays_Date', 'TestDays_TestType'], ['TestDays_ID' => $testDayIds]) as $testDayDatum) {
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

            foreach ($dataModels as $dataModel) {
                if ($dataModel->CowTests_HideFlag == 1) {
                    continue;
                }
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->CowTests_ID, Testdays::MIGRATION_ID_PREFIX);
                if (in_array($newModel->migration_id, $existingMigrationIds)) {
                    Yii::$app->controller->stdout("Milk record {$n} with migration id: {$newModel->migration_id} already saved. Ignored\n");
                    $n++;
                    continue;
                }
                $animalMigId = Helper::getMigrationId($dataModel->CowTests_CowID, Cows::MIGRATION_ID_PREFIX);
                $newModel->animal_id = $animalData[$animalMigId] ?? null;
                $newModel->event_date = $testDayData[$dataModel->CowTests_TDayID]['TestDays_Date'] ?? null;
                if ($newModel->event_date == '0000-00-00') {
                    $newModel->event_date = null;
                }

                //'animal_id','event_date' are required
                if (empty($newModel->animal_id)) {
                    Yii::$app->controller->stdout("Validation error on milk record {$n}: Animal Id cannot be blank.\n");
                    $n++;
                    continue;
                }
                if (empty($newModel->event_date)) {
                    Yii::$app->controller->stdout("Validation error on milk record {$n}: Event date cannot be blank.\n");
                    $n++;
                    continue;
                }
                $newModel->milk_test_type = $testDayData[$dataModel->CowTests_TDayID]['TestDays_TestType'] ?? null;

                $newModel->milkmor = ((float)$dataModel->CowTests_AMYield) / 10;
                $newModel->milkeve = ((float)$dataModel->CowTests_PMYield) / 10;
                $newModel->milkday = ((float)$dataModel->CowTests_Yield1) / 10;
                $newModel->milkfat = ((float)$dataModel->CowTests_FatP) / 100;
                $newModel->milkprot = ((float)$dataModel->CowTests_ProtP) / 100;
                $newModel->milklact = ((float)$dataModel->CowTests_LactP) / 100;
                $newModel->milksmc = $dataModel->CowTests_ICCC;

                $oldLactId = Helper::getMigrationId($dataModel->CowTests_LactID, Lacts::MIGRATION_ID_PREFIX);
                $newModel->lactation_id = $lactData[$oldLactId] ?? null;

                static::saveModel($newModel, $n, false);
                $n++;
            }
        }
    }
}
