<?php

namespace console\dataMigration\ke\models;

use backend\modules\core\models\AnimalHerd;
use backend\modules\core\models\Farm;
use Yii;

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
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%herds}}';
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
            [['Herds_ID', 'Herds_Name'], 'required'],
            [['Herds_ID', 'Herds_Farm', 'Herds_Owner'], 'number'],
            [['Herds_Species', 'Herds_CentreID', 'Herds_SubCentre', 'Herds_HistRecorded', 'Herds_PI2', 'Herds_PI3', 'Herds_PI4', 'Herds_PI5', 'Herds_Upload', 'Herds_Download', 'Herds_SiresRec', 'Herds_MGSRec', 'Herds_DamsRec', 'Herds_DOBRec', 'Herds_EIDRec', 'Herds_NLISRec', 'Herds_MRContID', 'Herds_HideFlag', 'Herds_Locked'], 'integer'],
            [['Herds_StartDate', 'Herds_StopDate', 'Herds_SeasonBreak', 'Herds_LastTest', 'Herds_LastARep', 'Herds_LastCCR', 'Herds_Modified'], 'safe'],
            [['Herds_Name'], 'string', 'max' => 14],
            [['Herds_ADHISID'], 'string', 'max' => 7],
            [['Herds_ADHISExclude', 'Herds_PTHerd', 'Herds_UseName', 'Herds_QuickUpd', 'Herds_MatingCur', 'Herds_UpdateCur', 'Herds_ImportNLIS', 'Herds_UseOldPIs', 'Herds_AvePIto100', 'Herds_UseGroups'], 'string', 'max' => 1],
            [['Herds_LRC', 'Herds_Flags1', 'Herds_Flags2', 'Herds_ModifiedBy'], 'string', 'max' => 10],
            [['Herds_UpdateMsgs', 'Herds_ImportMsgs', 'Herds_TestProcMsgs'], 'string', 'max' => 255],
            [['Herds_Name', 'Herds_HideFlag'], 'unique', 'targetAttribute' => ['Herds_Name', 'Herds_HideFlag']],
            [['Herds_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Herds_ID' => 'Herds ID',
            'Herds_Name' => 'Herds Name',
            'Herds_Species' => 'Herds Species',
            'Herds_Farm' => 'Herds Farm',
            'Herds_Owner' => 'Herds Owner',
            'Herds_ADHISID' => 'Herds Adhisid',
            'Herds_StartDate' => 'Herds Start Date',
            'Herds_StopDate' => 'Herds Stop Date',
            'Herds_CentreID' => 'Herds Centre ID',
            'Herds_SubCentre' => 'Herds Sub Centre',
            'Herds_ADHISExclude' => 'Herds Adhis Exclude',
            'Herds_HistRecorded' => 'Herds Hist Recorded',
            'Herds_PTHerd' => 'Herds Pt Herd',
            'Herds_UseName' => 'Herds Use Name',
            'Herds_QuickUpd' => 'Herds Quick Upd',
            'Herds_MatingCur' => 'Herds Mating Cur',
            'Herds_UpdateCur' => 'Herds Update Cur',
            'Herds_ImportNLIS' => 'Herds Import Nlis',
            'Herds_UseOldPIs' => 'Herds Use Old P Is',
            'Herds_AvePIto100' => 'Herds Ave P Ito100',
            'Herds_SeasonBreak' => 'Herds Season Break',
            'Herds_LastTest' => 'Herds Last Test',
            'Herds_LastARep' => 'Herds Last A Rep',
            'Herds_LastCCR' => 'Herds Last Ccr',
            'Herds_LRC' => 'Herds Lrc',
            'Herds_Flags1' => 'Herds Flags1',
            'Herds_Flags2' => 'Herds Flags2',
            'Herds_UseGroups' => 'Herds Use Groups',
            'Herds_PI2' => 'Herds Pi2',
            'Herds_PI3' => 'Herds Pi3',
            'Herds_PI4' => 'Herds Pi4',
            'Herds_PI5' => 'Herds Pi5',
            'Herds_Upload' => 'Herds Upload',
            'Herds_Download' => 'Herds Download',
            'Herds_SiresRec' => 'Herds Sires Rec',
            'Herds_MGSRec' => 'Herds Mgs Rec',
            'Herds_DamsRec' => 'Herds Dams Rec',
            'Herds_DOBRec' => 'Herds Dob Rec',
            'Herds_EIDRec' => 'Herds Eid Rec',
            'Herds_NLISRec' => 'Herds Nlis Rec',
            'Herds_UpdateMsgs' => 'Herds Update Msgs',
            'Herds_ImportMsgs' => 'Herds Import Msgs',
            'Herds_TestProcMsgs' => 'Herds Test Proc Msgs',
            'Herds_MRContID' => 'Herds Mr Cont ID',
            'Herds_Modified' => 'Herds Modified',
            'Herds_ModifiedBy' => 'Herds Modified By',
            'Herds_HideFlag' => 'Herds Hide Flag',
            'Herds_Locked' => 'Herds Locked',
        ];
    }

    public static function migrateData()
    {
        $query = static::find()->andWhere(['Herds_HideFlag' => 0, 'Herds_Species' => 0]);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(Constants::KLBA_ORG_NAME);
        $model = new AnimalHerd(['country_id' => $countryId, 'org_id' => $orgId]);
        foreach ($query->batch() as $i => $dataModels) {
            foreach ($dataModels as $dataModel) {
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->Herds_ID);
                $newModel->farm_id = self::getFarmId($dataModel->Herds_Farm);
                $newModel->reg_date = $dataModel->Herds_StartDate;
                $newModel->exit_date = $dataModel->Herds_StopDate;
                $newModel->centre_id = $dataModel->Herds_CentreID;
                $newModel->name = $dataModel->Herds_Name;
                static::saveModel($newModel, $n);
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
        $migrationId = Helper::getMigrationId($oldFarmId);

        $farmId = Farm::getScalar('id', ['migration_id' => $migrationId]);
        if (empty($farmId)) {
            return null;
        }
        return $farmId;
    }
}
