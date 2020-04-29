<?php

namespace console\dataMigration\mistro\stanley1;

use console\dataMigration\mistro\MigrationBase;
use console\dataMigration\mistro\MigrationInterface;

/**
 * This is the model class for table "testdays".
 *
 * @property string $TestDays_ID
 * @property float|null $TestDays_HerdID
 * @property int $TestDays_HerdGroup
 * @property string|null $TestDays_Date
 * @property int|null $TestDays_TestType
 * @property int|null $TestDays_Fresh
 * @property int|null $TestDays_ChgTests
 * @property int|null $TestDays_Termed
 * @property int|null $TestDays_Missed
 * @property int|null $TestDays_MilkArea
 * @property string|null $TestDays_UpdateDate
 * @property int|null $TestDays_UpdateStatus
 * @property string|null $TestDays_AveRecID
 * @property int|null $TestDays_CCBand1
 * @property int|null $TestDays_CCBand2
 * @property int|null $TestDays_CCBand3
 * @property int|null $TestDays_CCBand4
 * @property int|null $TestDays_CCBad
 * @property string|null $TestDays_AveRecID2
 * @property string|null $TestDays_AveRecID3
 * @property string|null $TestDays_AveRecID4
 * @property string|null $TestDays_AveRecID5
 * @property int|null $TestDays_YTDIn
 * @property int|null $TestDays_FTDIn
 * @property int|null $TestDays_PTDIn
 * @property int|null $TestDays_LTDIn
 * @property float|null $TestDays_YTDI
 * @property float|null $TestDays_FTDI
 * @property float|null $TestDays_PTDI
 * @property float|null $TestDays_LTDI
 * @property int $TestDays_Upload
 * @property int $TestDays_Download
 * @property string|null $TestDays_Modified
 * @property string|null $TestDays_ModifiedBy
 * @property int $TestDays_HideFlag
 * @property int $TestDays_Locked
 *
 * @property Cowtests $milkRecords
 */
class Testdays extends MigrationBase implements MigrationInterface
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%testdays}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TestDays_ID'], 'required'],
            [['TestDays_HerdID', 'TestDays_YTDI', 'TestDays_FTDI', 'TestDays_PTDI', 'TestDays_LTDI'], 'number'],
            [['TestDays_HerdGroup', 'TestDays_TestType', 'TestDays_Fresh', 'TestDays_ChgTests', 'TestDays_Termed', 'TestDays_Missed', 'TestDays_MilkArea', 'TestDays_UpdateStatus', 'TestDays_CCBand1', 'TestDays_CCBand2', 'TestDays_CCBand3', 'TestDays_CCBand4', 'TestDays_CCBad', 'TestDays_YTDIn', 'TestDays_FTDIn', 'TestDays_PTDIn', 'TestDays_LTDIn', 'TestDays_Upload', 'TestDays_Download', 'TestDays_HideFlag', 'TestDays_Locked'], 'integer'],
            [['TestDays_Date', 'TestDays_UpdateDate', 'TestDays_Modified'], 'safe'],
            [['TestDays_ID'], 'string', 'max' => 16],
            [['TestDays_AveRecID', 'TestDays_AveRecID2', 'TestDays_AveRecID3', 'TestDays_AveRecID4', 'TestDays_AveRecID5'], 'string', 'max' => 17],
            [['TestDays_ModifiedBy'], 'string', 'max' => 10],
            [['TestDays_HerdID', 'TestDays_Date', 'TestDays_HideFlag'], 'unique', 'targetAttribute' => ['TestDays_HerdID', 'TestDays_Date', 'TestDays_HideFlag']],
            [['TestDays_Date', 'TestDays_HerdID', 'TestDays_HideFlag'], 'unique', 'targetAttribute' => ['TestDays_Date', 'TestDays_HerdID', 'TestDays_HideFlag']],
            [['TestDays_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'TestDays_ID' => 'Test Days ID',
            'TestDays_HerdID' => 'Test Days Herd ID',
            'TestDays_HerdGroup' => 'Test Days Herd Group',
            'TestDays_Date' => 'Test Days Date',
            'TestDays_TestType' => 'Test Days Test Type',
            'TestDays_Fresh' => 'Test Days Fresh',
            'TestDays_ChgTests' => 'Test Days Chg Tests',
            'TestDays_Termed' => 'Test Days Termed',
            'TestDays_Missed' => 'Test Days Missed',
            'TestDays_MilkArea' => 'Test Days Milk Area',
            'TestDays_UpdateDate' => 'Test Days Update Date',
            'TestDays_UpdateStatus' => 'Test Days Update Status',
            'TestDays_AveRecID' => 'Test Days Ave Rec ID',
            'TestDays_CCBand1' => 'Test Days Cc Band1',
            'TestDays_CCBand2' => 'Test Days Cc Band2',
            'TestDays_CCBand3' => 'Test Days Cc Band3',
            'TestDays_CCBand4' => 'Test Days Cc Band4',
            'TestDays_CCBad' => 'Test Days Cc Bad',
            'TestDays_AveRecID2' => 'Test Days Ave Rec Id2',
            'TestDays_AveRecID3' => 'Test Days Ave Rec Id3',
            'TestDays_AveRecID4' => 'Test Days Ave Rec Id4',
            'TestDays_AveRecID5' => 'Test Days Ave Rec Id5',
            'TestDays_YTDIn' => 'Test Days Ytd In',
            'TestDays_FTDIn' => 'Test Days Ftd In',
            'TestDays_PTDIn' => 'Test Days Ptd In',
            'TestDays_LTDIn' => 'Test Days Ltd In',
            'TestDays_YTDI' => 'Test Days Ytdi',
            'TestDays_FTDI' => 'Test Days Ftdi',
            'TestDays_PTDI' => 'Test Days Ptdi',
            'TestDays_LTDI' => 'Test Days Ltdi',
            'TestDays_Upload' => 'Test Days Upload',
            'TestDays_Download' => 'Test Days Download',
            'TestDays_Modified' => 'Test Days Modified',
            'TestDays_ModifiedBy' => 'Test Days Modified By',
            'TestDays_HideFlag' => 'Test Days Hide Flag',
            'TestDays_Locked' => 'Test Days Locked',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkRecords()
    {
        return $this->hasMany(Cowtests::class, ['CowTests_TDayID' => 'TestDays_ID']);
    }

    public static function migrateData()
    {
    }

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'MILKING_EVENT_';
    }


}
