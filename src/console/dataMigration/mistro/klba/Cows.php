<?php

namespace console\dataMigration\mistro\klba;

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalHerd;
use console\dataMigration\mistro\Helper;
use console\dataMigration\mistro\MigrationBase;
use console\dataMigration\mistro\MigrationInterface;
use Yii;

/**
 * This is the model class for table "cows".
 *
 * @property string $Cows_ID
 * @property int $Cows_Species
 * @property float $Cows_Herd
 * @property string $Cows_HIONo
 * @property string|null $Cows_HIONoSorted
 * @property string $Cows_HRID
 * @property string|null $Cows_HRIDSorted
 * @property string|null $Cows_RegName
 * @property string|null $Cows_EarTag
 * @property string $Cows_Sex
 * @property string|null $Cows_Birth
 * @property string|null $Cows_Sire
 * @property string|null $Cows_SireF
 * @property string|null $Cows_Dam
 * @property string|null $Cows_BreedS
 * @property int|null $Cows_ABVStatus
 * @property int|null $Cows_ABVASI
 * @property string|null $Cows_NLISID
 * @property string|null $Cows_ElecID
 * @property string|null $Cows_Registered
 * @property string|null $Cows_HerdBook
 * @property string|null $Cows_RegDate
 * @property int|null $Cows_Grade
 * @property string|null $Cows_Remark
 * @property int|null $Cows_PrevClinical
 * @property int|null $Cows_CurClinical
 * @property int|null $Cows_CowStatus
 * @property string|null $Cows_DamNatID
 * @property string|null $Cows_SireNatID
 * @property string|null $Cows_CCString
 * @property float|null $Cows_BirthWt
 * @property string|null $Cows_DateIn
 * @property int|null $Cows_Origin
 * @property int|null $Cows_HerdGroup
 * @property int|null $Cows_CurAgeGroup
 * @property int|null $Cows_DPCentre
 * @property string|null $Cows_LastCalvDate
 * @property int|null $Cows_LastCalvCode
 * @property string|null $Cows_LastDryOff
 * @property int|null $Cows_LastDryOffCode
 * @property string|null $Cows_LastTest
 * @property string|null $Cows_TermDate
 * @property int|null $Cows_TermCode
 * @property int|null $Cows_UnrecLacs
 * @property int|null $Cows_Matings
 * @property string|null $Cows_MGSire
 * @property string|null $Cows_UpdDate
 * @property int|null $Cows_UpdStatus
 * @property string|null $Cows_LastHeat
 * @property string|null $Cows_PrefSire1
 * @property string|null $Cows_PrefSire2
 * @property string|null $Cows_LastServ
 * @property int $Cows_Gestn
 * @property string|null $Cows_LastSire
 * @property string|null $Cows_PrevServ
 * @property string|null $Cows_PrevSire
 * @property string|null $Cows_LastPD
 * @property int|null $Cows_Pregnant
 * @property string|null $Cows_DueDate
 * @property string|null $Cows_SireDue
 * @property string|null $Cows_DryOff1
 * @property string|null $Cows_DryOffDate
 * @property string|null $Cows_ProblemRemark
 * @property string|null $Cows_ProblemDate
 * @property float|null $Cows_Problem
 * @property string|null $Cows_Date
 * @property int|null $Cows_Yield1
 * @property int|null $Cows_Fat
 * @property int|null $Cows_Prot
 * @property int|null $Cows_Lact
 * @property int|null $Cows_ICCC
 * @property float|null $Cows_Dollar
 * @property float|null $Cows_YTDDollar
 * @property int|null $Cows_Exclude
 * @property string|null $Cows_CLStatID
 * @property string|null $Cows_PLStatID
 * @property int $Cows_Upload
 * @property int $Cows_Download
 * @property int|null $Cows_Flag
 * @property string|null $Cows_Modified
 * @property string|null $Cows_ModifiedBy
 * @property int $Cows_HideFlag
 * @property int $Cows_Locked
 */
class Cows extends MigrationBase implements MigrationInterface
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cows}}';
    }

    public static function migrateData()
    {
        $query = static::find()->andWhere(['Cows_HideFlag' => 0, 'Cows_Species' => 0]);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(\console\dataMigration\mistro\Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(static::getOrgName());
        $model = new Animal(['country_id' => $countryId, 'org_id' => $orgId, 'scenario' => Animal::SCENARIO_MISTRO_DB_COW_UPLOAD]);
        foreach ($query->batch() as $i => $dataModels) {
            foreach ($dataModels as $dataModel) {
                $herdModel = static::getHerd($dataModel->Cows_Herd);
                if (null === $herdModel) {
                    Yii::$app->controller->stdout("ERROR: Herd ID {$dataModel->Cows_Herd} does not exist.\n");
                    $n++;
                    continue;
                }
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->Cows_ID, static::getMigrationIdPrefix());
                $newModel->herd_id = $herdModel->id;
                $newModel->farm_id = $herdModel->farm_id;
                $newModel->tag_id = $dataModel->Cows_HIONo;
                $newModel->name = $dataModel->Cows_RegName;
                $newModel->animal_eartag_id = $dataModel->Cows_EarTag;
                if (strtolower($dataModel->Cows_Sex) == 'm') {
                    $newModel->sex = 1;
                } elseif (strtolower($dataModel->Cows_Sex) == 'f') {
                    $newModel->sex = 2;
                }
                $newModel->birthdate = $dataModel->Cows_Birth;
                $newModel->sire_tag_id = Helper::getMigrationId($dataModel->Cows_Sire, static::getBullMigrationIdPrefix());
                $newModel->dam_tag_id = Helper::getMigrationId($dataModel->Cows_Dam, static::getMigrationIdPrefix());
                $newModel->breed_composition_details = $dataModel->Cows_BreedS;
                $newModel->herd_book_no = $dataModel->Cows_HerdBook;
                $newModel->entry_date = $dataModel->Cows_RegDate;
                $newModel->animal_grade = $dataModel->Cows_Grade;
                $newModel->animal_notes = $dataModel->Cows_Remark;
                $newModel->cow_status = $dataModel->Cows_CowStatus;
                if ($dataModel->Cows_Origin == 0) {
                    $newModel->entry_type = 2;
                } elseif ($dataModel->Cows_Origin == 1) {
                    $newModel->entry_type = 1;
                } elseif ($dataModel->Cows_Origin == 2) {
                    $newModel->entry_type = 3;
                }
                $newModel->animal_exit_date = $dataModel->Cows_TermDate;
                $newModel->animal_exit_code = $dataModel->Cows_TermCode;
                $newModel->reg_date = !empty($dataModel->Cows_Date) ? $dataModel->Cows_Date : $dataModel->Cows_Modified;
                if ($newModel->reg_date == '0000-00-00') {
                    $newModel->reg_date = null;
                }

                static::saveModel($newModel, $n);
                $n++;
            }
        }
    }

    /**
     * @param int $oldHerdId
     * @return array|AnimalHerd|\yii\db\ActiveRecord|null
     */
    public static function getHerd($oldHerdId)
    {
        $migrationId = Helper::getMigrationId($oldHerdId, Herds::getMigrationIdPrefix());
        return AnimalHerd::find()->andWhere(['migration_id' => $migrationId])->one();
    }

    public static function getODKBreedCode($mistroCode)
    {
        $map = [
            'F' => 0,
            'J' => 0,
            ''
        ];
    }

    public static function getMistroExitCodes($id)
    {
        $id = (string)$id;
        $map = [
            '1' => 'S1',
            '2' => 'S2',
            '3' => 'S3',
            '4' => 'S4',
            '5' => 'S5',
            '6' => 'S6',
            '7' => 'S7',
            '8' => 'S8',
            '9' => 'S9',
            '10' => 'X3',
            '11' => 'X2',
            '12' => 'X1',
            '13' => 'X5',
            '14' => 'X4',
            '15' => 'X6',
            '16' => 'X7',
            '17' => 'X9',
            '18' => 'X8',
            '-1' => 'XCENTRE',
            '19' => 'X10',
            '20' => 'SE',
        ];

        return $map[$id] ?? $id;
    }

    public static function updateSiresAndDams()
    {
        $condition = '[[migration_id]] IS NOT NULL';
        $params = [];
        $query = Animal::find()->andWhere($condition, $params);
        $n = 1;
        /* @var $models Animal[] */
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                if ($n < 0) {
                    $n++;
                    Yii::$app->controller->stdout("Animal Record: {$n} already updated. Ignored\n");
                    continue;
                }
                if (empty($model->migration_id)) {
                    $n++;
                    $org = static::getOrgName();
                    Yii::$app->controller->stdout("The animal id: {$model->id} is not from {$org}. Ignored\n");
                    continue;
                }
                if (!empty($model->sire_tag_id)) {
                    if (!empty($model->sire_id)) {
                        $n++;
                        Yii::$app->controller->stdout("Animal Record: {$n} already updated. Ignored\n");
                        continue;
                    }
                    $sire = Animal::getOneRow(['id', 'tag_id'], ['migration_id' => $model->sire_tag_id]);
                    if (!empty($sire)) {
                        $model->sire_id = $sire['id'];
                        $model->sire_tag_id = $sire['tag_id'];
                    }
                }
                if (!empty($model->dam_tag_id)) {
                    if (!empty($model->dam_id)) {
                        $n++;
                        Yii::$app->controller->stdout("Animal Record: {$n} already updated. Ignored\n");
                        continue;
                    }
                    $dam = Animal::getOneRow(['id', 'tag_id'], ['migration_id' => $model->dam_tag_id]);
                    if (!empty($dam)) {
                        $model->dam_id = $dam['id'];
                        $model->dam_tag_id = $dam['tag_id'];
                    }
                }
                if (!empty($model->sire_tag_id) || !empty($model->dam_tag_id)) {
                    $model->save(false);
                    Yii::$app->controller->stdout("Updated {$n} animal records\n");
                }
                $n++;
            }
        }
    }

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'COWS_';
    }

    public static function getBullMigrationIdPrefix()
    {
        return Bulls::getMigrationIdPrefix();
    }
}