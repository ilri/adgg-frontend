<?php

namespace console\dataMigration\ke\models;

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
class Cowtests extends MigrationBase
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
}
