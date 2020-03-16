<?php

namespace console\dataMigration\ke\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CalvingEvent;
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
    const MIGRATION_ID_PREFIX = 'KLBA_CALVING_EVENT_';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lacts}}';
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
            [['Lacts_ID', 'Lacts_CowID', 'Lacts_InitDate', 'Lacts_InitCode'], 'required'],
            [['Lacts_ID', 'Lacts_CalvingHerd'], 'number'],
            [['Lacts_InitDate', 'Lacts_TermDate', 'Lacts_Modified'], 'safe'],
            [['Lacts_InitCode', 'Lacts_LactNo', 'Lacts_TermCode', 'Lacts_Hormone', 'Lacts_TFreq', 'Lacts_Upload', 'Lacts_Download', 'Lacts_Flag', 'Lacts_HideFlag', 'Lacts_Locked'], 'integer'],
            [['Lacts_CowID'], 'string', 'max' => 11],
            [['Lacts_Accept'], 'string', 'max' => 1],
            [['Lacts_StatID'], 'string', 'max' => 14],
            [['Lacts_ModifiedBy'], 'string', 'max' => 10],
            [['Lacts_CowID', 'Lacts_InitDate', 'Lacts_HideFlag'], 'unique', 'targetAttribute' => ['Lacts_CowID', 'Lacts_InitDate', 'Lacts_HideFlag']],
            [['Lacts_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Lacts_ID' => 'Lacts ID',
            'Lacts_CowID' => 'Lacts Cow ID',
            'Lacts_InitDate' => 'Lacts Init Date',
            'Lacts_InitCode' => 'Lacts Init Code',
            'Lacts_LactNo' => 'Lacts Lact No',
            'Lacts_CalvingHerd' => 'Lacts Calving Herd',
            'Lacts_TermDate' => 'Lacts Term Date',
            'Lacts_TermCode' => 'Lacts Term Code',
            'Lacts_Hormone' => 'Lacts Hormone',
            'Lacts_TFreq' => 'Lacts T Freq',
            'Lacts_Accept' => 'Lacts Accept',
            'Lacts_StatID' => 'Lacts Stat ID',
            'Lacts_Upload' => 'Lacts Upload',
            'Lacts_Download' => 'Lacts Download',
            'Lacts_Flag' => 'Lacts Flag',
            'Lacts_Modified' => 'Lacts Modified',
            'Lacts_ModifiedBy' => 'Lacts Modified By',
            'Lacts_HideFlag' => 'Lacts Hide Flag',
            'Lacts_Locked' => 'Lacts Locked',
        ];
    }

    public static function migrateData()
    {
        $query = static::find()->andWhere(['Lacts_HideFlag' => 0]);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(Constants::KLBA_ORG_NAME);
        $model = new CalvingEvent(['country_id' => $countryId, 'org_id' => $orgId, 'event_type' => AnimalEvent::EVENT_TYPE_CALVING,'scenario' =>CalvingEvent::SCENARIO_KLBA_UPLOAD ]);
        foreach ($query->batch() as $i => $dataModels) {
            foreach ($dataModels as $dataModel) {
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->Lacts_ID, self::MIGRATION_ID_PREFIX);
                $newModel->animal_id = self::getAnimalId($dataModel->Lacts_CowID);
                $newModel->event_date = $dataModel->Lacts_InitDate;
                if ($newModel->event_date == '0000-00-00') {
                    $newModel->event_date = null;
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
                static::saveModel($newModel, $n);
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
        $migrationId = Helper::getMigrationId($oldAnimalId, Cows::MIGRATION_ID_PREFIX);
        $animalId = Animal::getScalar('id', ['migration_id' => $migrationId]);
        if (empty($animalId)) {
            return null;
        }
        return $animalId;
    }
}
