<?php

namespace console\dataMigration\mistro\adggplatform;

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