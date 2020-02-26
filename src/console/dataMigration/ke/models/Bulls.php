<?php

namespace console\dataMigration\ke\models;

use common\models\ActiveRecord;
use Yii;

/**
 * This is the model class for table "bulls".
 *
 * @property string $Bulls_ID
 * @property int $Bulls_species
 * @property string $Bulls_Nasis2
 * @property string $Bulls_Nasis1
 * @property string $Bulls_RegName
 * @property string|null $Bulls_RegNasis
 * @property string|null $Bulls_ShortName
 * @property string|null $Bulls_ActiveNasis
 * @property float|null $Bulls_Herd
 * @property string|null $Bulls_Common2
 * @property string|null $Bulls_EarTag
 * @property string|null $Bulls_Birth
 * @property string|null $Bulls_Sire
 * @property string|null $Bulls_SireF
 * @property string|null $Bulls_Dam
 * @property string|null $Bulls_BreedS
 * @property string|null $Bulls_Registered
 * @property string|null $Bulls_HerdBookCty
 * @property string|null $Bulls_HerdBook
 * @property int|null $Bulls_Origin
 * @property int|null $Bulls_Grade
 * @property string|null $Bulls_TermDate
 * @property int|null $Bulls_TermCode
 * @property string|null $Bulls_NLISID
 * @property string|null $Bulls_MGSire
 * @property string|null $Bulls_Defects
 * @property string|null $Bulls_PTBull
 * @property string|null $Bulls_SemenAvail
 * @property string|null $Bulls_InternatID
 * @property string|null $Bulls_SexedAvail
 * @property int|null $Bulls_DPCentre
 * @property string|null $Bulls_OwnerCode
 * @property int $Bulls_Upload
 * @property int $Bulls_Download
 * @property string|null $Bulls_Modified
 * @property string|null $Bulls_ModifiedBy
 * @property int $Bulls_HideFlag
 * @property int $Bulls_Locked
 */
class Bulls extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bulls}}';
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
            [['Bulls_ID', 'Bulls_Nasis2', 'Bulls_Nasis1', 'Bulls_RegName'], 'required'],
            [['Bulls_species', 'Bulls_Origin', 'Bulls_Grade', 'Bulls_TermCode', 'Bulls_DPCentre', 'Bulls_Upload', 'Bulls_Download', 'Bulls_HideFlag', 'Bulls_Locked'], 'integer'],
            [['Bulls_Herd'], 'number'],
            [['Bulls_Birth', 'Bulls_TermDate', 'Bulls_SemenAvail', 'Bulls_SexedAvail', 'Bulls_Modified'], 'safe'],
            [['Bulls_ID', 'Bulls_Sire', 'Bulls_Dam', 'Bulls_MGSire'], 'string', 'max' => 11],
            [['Bulls_Nasis2', 'Bulls_Nasis1', 'Bulls_Common2', 'Bulls_EarTag'], 'string', 'max' => 12],
            [['Bulls_RegName'], 'string', 'max' => 40],
            [['Bulls_RegNasis', 'Bulls_ActiveNasis', 'Bulls_SireF', 'Bulls_Registered', 'Bulls_PTBull'], 'string', 'max' => 1],
            [['Bulls_ShortName'], 'string', 'max' => 15],
            [['Bulls_BreedS'], 'string', 'max' => 4],
            [['Bulls_HerdBookCty', 'Bulls_OwnerCode'], 'string', 'max' => 3],
            [['Bulls_HerdBook'], 'string', 'max' => 20],
            [['Bulls_NLISID'], 'string', 'max' => 16],
            [['Bulls_Defects'], 'string', 'max' => 8],
            [['Bulls_InternatID'], 'string', 'max' => 19],
            [['Bulls_ModifiedBy'], 'string', 'max' => 10],
            [['Bulls_Nasis2', 'Bulls_HideFlag'], 'unique', 'targetAttribute' => ['Bulls_Nasis2', 'Bulls_HideFlag']],
            [['Bulls_RegName', 'Bulls_HideFlag'], 'unique', 'targetAttribute' => ['Bulls_RegName', 'Bulls_HideFlag']],
            [['Bulls_Nasis1', 'Bulls_HideFlag'], 'unique', 'targetAttribute' => ['Bulls_Nasis1', 'Bulls_HideFlag']],
            [['Bulls_HerdBook', 'Bulls_Nasis2', 'Bulls_HideFlag'], 'unique', 'targetAttribute' => ['Bulls_HerdBook', 'Bulls_Nasis2', 'Bulls_HideFlag']],
            [['Bulls_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Bulls_ID' => 'Bulls ID',
            'Bulls_species' => 'Bulls Species',
            'Bulls_Nasis2' => 'Bulls Nasis2',
            'Bulls_Nasis1' => 'Bulls Nasis1',
            'Bulls_RegName' => 'Bulls Reg Name',
            'Bulls_RegNasis' => 'Bulls Reg Nasis',
            'Bulls_ShortName' => 'Bulls Short Name',
            'Bulls_ActiveNasis' => 'Bulls Active Nasis',
            'Bulls_Herd' => 'Bulls Herd',
            'Bulls_Common2' => 'Bulls Common2',
            'Bulls_EarTag' => 'Bulls Ear Tag',
            'Bulls_Birth' => 'Bulls Birth',
            'Bulls_Sire' => 'Bulls Sire',
            'Bulls_SireF' => 'Bulls Sire F',
            'Bulls_Dam' => 'Bulls Dam',
            'Bulls_BreedS' => 'Bulls Breed S',
            'Bulls_Registered' => 'Bulls Registered',
            'Bulls_HerdBookCty' => 'Bulls Herd Book Cty',
            'Bulls_HerdBook' => 'Bulls Herd Book',
            'Bulls_Origin' => 'Bulls Origin',
            'Bulls_Grade' => 'Bulls Grade',
            'Bulls_TermDate' => 'Bulls Term Date',
            'Bulls_TermCode' => 'Bulls Term Code',
            'Bulls_NLISID' => 'Bulls Nlisid',
            'Bulls_MGSire' => 'Bulls Mg Sire',
            'Bulls_Defects' => 'Bulls Defects',
            'Bulls_PTBull' => 'Bulls Pt Bull',
            'Bulls_SemenAvail' => 'Bulls Semen Avail',
            'Bulls_InternatID' => 'Bulls Internat ID',
            'Bulls_SexedAvail' => 'Bulls Sexed Avail',
            'Bulls_DPCentre' => 'Bulls Dp Centre',
            'Bulls_OwnerCode' => 'Bulls Owner Code',
            'Bulls_Upload' => 'Bulls Upload',
            'Bulls_Download' => 'Bulls Download',
            'Bulls_Modified' => 'Bulls Modified',
            'Bulls_ModifiedBy' => 'Bulls Modified By',
            'Bulls_HideFlag' => 'Bulls Hide Flag',
            'Bulls_Locked' => 'Bulls Locked',
        ];
    }
}
