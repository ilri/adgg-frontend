<?php

namespace console\dataMigration\ke\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalHerd;
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
    const MIGRATION_ID_PREFIX = 'STANLEY_COWS_';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cows}}';
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
            [['Cows_ID', 'Cows_Herd', 'Cows_HIONo', 'Cows_HRID'], 'required'],
            [['Cows_Species', 'Cows_ABVStatus', 'Cows_ABVASI', 'Cows_Grade', 'Cows_PrevClinical', 'Cows_CurClinical', 'Cows_CowStatus', 'Cows_Origin', 'Cows_HerdGroup', 'Cows_CurAgeGroup', 'Cows_DPCentre', 'Cows_LastCalvCode', 'Cows_LastDryOffCode', 'Cows_TermCode', 'Cows_UnrecLacs', 'Cows_Matings', 'Cows_UpdStatus', 'Cows_Gestn', 'Cows_Pregnant', 'Cows_Yield1', 'Cows_Fat', 'Cows_Prot', 'Cows_Lact', 'Cows_ICCC', 'Cows_Exclude', 'Cows_Upload', 'Cows_Download', 'Cows_Flag', 'Cows_HideFlag', 'Cows_Locked'], 'integer'],
            [['Cows_Herd', 'Cows_BirthWt', 'Cows_Problem', 'Cows_Dollar', 'Cows_YTDDollar'], 'number'],
            [['Cows_Birth', 'Cows_RegDate', 'Cows_DateIn', 'Cows_LastCalvDate', 'Cows_LastDryOff', 'Cows_LastTest', 'Cows_TermDate', 'Cows_UpdDate', 'Cows_LastHeat', 'Cows_LastServ', 'Cows_PrevServ', 'Cows_LastPD', 'Cows_DueDate', 'Cows_DryOff1', 'Cows_DryOffDate', 'Cows_ProblemDate', 'Cows_Date', 'Cows_Modified'], 'safe'],
            [['Cows_ID', 'Cows_Sire', 'Cows_Dam', 'Cows_DamNatID', 'Cows_SireNatID', 'Cows_MGSire', 'Cows_PrefSire1', 'Cows_PrefSire2', 'Cows_LastSire', 'Cows_PrevSire', 'Cows_SireDue'], 'string', 'max' => 11],
            [['Cows_HIONo', 'Cows_HRID', 'Cows_NLISID'], 'string', 'max' => 16],
            [['Cows_HIONoSorted', 'Cows_HRIDSorted'], 'string', 'max' => 30],
            [['Cows_RegName'], 'string', 'max' => 35],
            [['Cows_EarTag'], 'string', 'max' => 12],
            [['Cows_Sex', 'Cows_SireF', 'Cows_Registered'], 'string', 'max' => 1],
            [['Cows_BreedS'], 'string', 'max' => 4],
            [['Cows_ElecID'], 'string', 'max' => 23],
            [['Cows_HerdBook'], 'string', 'max' => 20],
            [['Cows_Remark'], 'string', 'max' => 50],
            [['Cows_CCString'], 'string', 'max' => 13],
            [['Cows_ProblemRemark'], 'string', 'max' => 25],
            [['Cows_CLStatID', 'Cows_PLStatID'], 'string', 'max' => 14],
            [['Cows_ModifiedBy'], 'string', 'max' => 10],
            [['Cows_Herd', 'Cows_HIONo', 'Cows_HideFlag'], 'unique', 'targetAttribute' => ['Cows_Herd', 'Cows_HIONo', 'Cows_HideFlag']],
            [['Cows_Herd', 'Cows_HRID', 'Cows_HIONo', 'Cows_HideFlag'], 'unique', 'targetAttribute' => ['Cows_Herd', 'Cows_HRID', 'Cows_HIONo', 'Cows_HideFlag']],
            [['Cows_Herd', 'Cows_ID', 'Cows_HideFlag'], 'unique', 'targetAttribute' => ['Cows_Herd', 'Cows_ID', 'Cows_HideFlag']],
            [['Cows_Herd', 'Cows_HIONoSorted', 'Cows_HideFlag'], 'unique', 'targetAttribute' => ['Cows_Herd', 'Cows_HIONoSorted', 'Cows_HideFlag']],
            [['Cows_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Cows_ID' => 'Cows ID',
            'Cows_Species' => 'Cows Species',
            'Cows_Herd' => 'Cows Herd',
            'Cows_HIONo' => 'Cows Hio No',
            'Cows_HIONoSorted' => 'Cows Hio No Sorted',
            'Cows_HRID' => 'Cows Hrid',
            'Cows_HRIDSorted' => 'Cows Hrid Sorted',
            'Cows_RegName' => 'Cows Reg Name',
            'Cows_EarTag' => 'Cows Ear Tag',
            'Cows_Sex' => 'Cows Sex',
            'Cows_Birth' => 'Cows Birth',
            'Cows_Sire' => 'Cows Sire',
            'Cows_SireF' => 'Cows Sire F',
            'Cows_Dam' => 'Cows Dam',
            'Cows_BreedS' => 'Cows Breed S',
            'Cows_ABVStatus' => 'Cows Abv Status',
            'Cows_ABVASI' => 'Cows Abvasi',
            'Cows_NLISID' => 'Cows Nlisid',
            'Cows_ElecID' => 'Cows Elec ID',
            'Cows_Registered' => 'Cows Registered',
            'Cows_HerdBook' => 'Cows Herd Book',
            'Cows_RegDate' => 'Cows Reg Date',
            'Cows_Grade' => 'Cows Grade',
            'Cows_Remark' => 'Cows Remark',
            'Cows_PrevClinical' => 'Cows Prev Clinical',
            'Cows_CurClinical' => 'Cows Cur Clinical',
            'Cows_CowStatus' => 'Cows Cow Status',
            'Cows_DamNatID' => 'Cows Dam Nat ID',
            'Cows_SireNatID' => 'Cows Sire Nat ID',
            'Cows_CCString' => 'Cows Cc String',
            'Cows_BirthWt' => 'Cows Birth Wt',
            'Cows_DateIn' => 'Cows Date In',
            'Cows_Origin' => 'Cows Origin',
            'Cows_HerdGroup' => 'Cows Herd Group',
            'Cows_CurAgeGroup' => 'Cows Cur Age Group',
            'Cows_DPCentre' => 'Cows Dp Centre',
            'Cows_LastCalvDate' => 'Cows Last Calv Date',
            'Cows_LastCalvCode' => 'Cows Last Calv Code',
            'Cows_LastDryOff' => 'Cows Last Dry Off',
            'Cows_LastDryOffCode' => 'Cows Last Dry Off Code',
            'Cows_LastTest' => 'Cows Last Test',
            'Cows_TermDate' => 'Cows Term Date',
            'Cows_TermCode' => 'Cows Term Code',
            'Cows_UnrecLacs' => 'Cows Unrec Lacs',
            'Cows_Matings' => 'Cows Matings',
            'Cows_MGSire' => 'Cows Mg Sire',
            'Cows_UpdDate' => 'Cows Upd Date',
            'Cows_UpdStatus' => 'Cows Upd Status',
            'Cows_LastHeat' => 'Cows Last Heat',
            'Cows_PrefSire1' => 'Cows Pref Sire1',
            'Cows_PrefSire2' => 'Cows Pref Sire2',
            'Cows_LastServ' => 'Cows Last Serv',
            'Cows_Gestn' => 'Cows Gestn',
            'Cows_LastSire' => 'Cows Last Sire',
            'Cows_PrevServ' => 'Cows Prev Serv',
            'Cows_PrevSire' => 'Cows Prev Sire',
            'Cows_LastPD' => 'Cows Last Pd',
            'Cows_Pregnant' => 'Cows Pregnant',
            'Cows_DueDate' => 'Cows Due Date',
            'Cows_SireDue' => 'Cows Sire Due',
            'Cows_DryOff1' => 'Cows Dry Off1',
            'Cows_DryOffDate' => 'Cows Dry Off Date',
            'Cows_ProblemRemark' => 'Cows Problem Remark',
            'Cows_ProblemDate' => 'Cows Problem Date',
            'Cows_Problem' => 'Cows Problem',
            'Cows_Date' => 'Cows Date',
            'Cows_Yield1' => 'Cows Yield1',
            'Cows_Fat' => 'Cows Fat',
            'Cows_Prot' => 'Cows Prot',
            'Cows_Lact' => 'Cows Lact',
            'Cows_ICCC' => 'Cows Iccc',
            'Cows_Dollar' => 'Cows Dollar',
            'Cows_YTDDollar' => 'Cows Ytd Dollar',
            'Cows_Exclude' => 'Cows Exclude',
            'Cows_CLStatID' => 'Cows Cl Stat ID',
            'Cows_PLStatID' => 'Cows Pl Stat ID',
            'Cows_Upload' => 'Cows Upload',
            'Cows_Download' => 'Cows Download',
            'Cows_Flag' => 'Cows Flag',
            'Cows_Modified' => 'Cows Modified',
            'Cows_ModifiedBy' => 'Cows Modified By',
            'Cows_HideFlag' => 'Cows Hide Flag',
            'Cows_Locked' => 'Cows Locked',
        ];
    }

    public static function migrateData()
    {
        $query = static::find()->andWhere(['Cows_HideFlag' => 0, 'Cows_Species' => 0]);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(Constants::ORG_NAME);
        $model = new Animal(['country_id' => $countryId, 'org_id' => $orgId, 'scenario' => Animal::SCENARIO_MISTRO_DB_COW_UPLOAD]);
        foreach ($query->batch() as $i => $dataModels) {
            foreach ($dataModels as $dataModel) {
                $herdModel = self::getHerd($dataModel->Cows_Herd);
                if (null === $herdModel) {
                    Yii::$app->controller->stdout("ERROR: Herd ID {$dataModel->Cows_Herd} does not exist.\n");
                    continue;
                }
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->Cows_ID, self::MIGRATION_ID_PREFIX);
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
                $newModel->sire_tag_id = Helper::getMigrationId($dataModel->Cows_Sire, Bulls::MIGRATION_ID_PREFIX);
                $newModel->dam_tag_id = Helper::getMigrationId($dataModel->Cows_Dam, self::MIGRATION_ID_PREFIX);
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
        $migrationId = Helper::getMigrationId($oldHerdId);
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
        $condition = '[[migration_id]] IS NOT NULL AND ([[dam_id]] IS NULL OR [[sire_id]] IS NULL)';
        $params = [];
        $query = Animal::find()->andWhere($condition, $params);
        $n = 1;
        /* @var $models Animal[] */
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                if($n<38000){
                    $n++;
                    Yii::$app->controller->stdout("Animal Record: {$n} already updated. Ignored\n");
                    continue;
                }
                if (empty($model->migration_id)) {
                    Yii::$app->controller->stdout("The animal id: {$model->id} is not from KLBA. Ignored\n");
                    continue;
                }
                if (!empty($model->sire_tag_id)) {
                    $sire = Animal::getOneRow(['id', 'tag_id'], ['migration_id' => $model->sire_tag_id]);
                    if (!empty($sire)) {
                        $model->sire_id = $sire['id'];
                        $model->sire_tag_id = $sire['tag_id'];
                    }
                }
                if (!empty($model->dam_tag_id)) {
                    $dam = Animal::getOneRow(['id', 'tag_id'], ['migration_id' => $model->dam_tag_id]);
                    if (!empty($dam)) {
                        $model->dam_id = $dam['id'];
                        $model->dam_tag_id = $dam['tag_id'];
                    }
                }
                $model->save(false);
                Yii::$app->controller->stdout("Updated {$n} animal records\n");
                $n++;
            }
        }
    }
}