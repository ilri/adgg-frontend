<?php

namespace console\dataMigration\ke\models;

use backend\modules\core\models\Client;
use backend\modules\core\models\Farm;
use Yii;

/**
 * This is the model class for table "farms".
 *
 * @property float $Farms_ID
 * @property string $Farms_SHNo
 * @property float|null $Farms_Owner
 * @property float|null $Farms_Share
 * @property string|null $Farms_Address
 * @property int|null $Farms_Units
 * @property string|null $Farms_EUID
 * @property string|null $Farms_Lat
 * @property string|null $Farms_Long
 * @property string|null $Farms_Location
 * @property int|null $Farms_TotArea
 * @property int|null $Farms_MilkArea
 * @property int|null $Farms_IrrArea
 * @property string|null $Farms_Flags1
 * @property string|null $Farms_Flags2
 * @property int $Farms_Upload
 * @property int $Farms_Download
 * @property string|null $Farms_Modified
 * @property string|null $Farms_ModifiedBy
 * @property int $Farms_HideFlag
 * @property int $Farms_Locked
 */
class Farms extends MigrationBase implements MigrationInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%farms}}';
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
            [['Farms_ID', 'Farms_SHNo'], 'required'],
            [['Farms_ID', 'Farms_Owner', 'Farms_Share'], 'number'],
            [['Farms_Units', 'Farms_TotArea', 'Farms_MilkArea', 'Farms_IrrArea', 'Farms_Upload', 'Farms_Download', 'Farms_HideFlag', 'Farms_Locked'], 'integer'],
            [['Farms_Modified'], 'safe'],
            [['Farms_SHNo'], 'string', 'max' => 14],
            [['Farms_Address'], 'string', 'max' => 35],
            [['Farms_EUID'], 'string', 'max' => 7],
            [['Farms_Lat', 'Farms_Long'], 'string', 'max' => 11],
            [['Farms_Location'], 'string', 'max' => 5],
            [['Farms_Flags1', 'Farms_Flags2', 'Farms_ModifiedBy'], 'string', 'max' => 10],
            [['Farms_SHNo', 'Farms_HideFlag'], 'unique', 'targetAttribute' => ['Farms_SHNo', 'Farms_HideFlag']],
            [['Farms_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Farms_ID' => 'Farms ID',
            'Farms_SHNo' => 'Farms Sh No',
            'Farms_Owner' => 'Farms Owner',
            'Farms_Share' => 'Farms Share',
            'Farms_Address' => 'Farms Address',
            'Farms_Units' => 'Farms Units',
            'Farms_EUID' => 'Farms Euid',
            'Farms_Lat' => 'Farms Lat',
            'Farms_Long' => 'Farms Long',
            'Farms_Location' => 'Farms Location',
            'Farms_TotArea' => 'Farms Tot Area',
            'Farms_MilkArea' => 'Farms Milk Area',
            'Farms_IrrArea' => 'Farms Irr Area',
            'Farms_Flags1' => 'Farms Flags1',
            'Farms_Flags2' => 'Farms Flags2',
            'Farms_Upload' => 'Farms Upload',
            'Farms_Download' => 'Farms Download',
            'Farms_Modified' => 'Farms Modified',
            'Farms_ModifiedBy' => 'Farms Modified By',
            'Farms_HideFlag' => 'Farms Hide Flag',
            'Farms_Locked' => 'Farms Locked',
        ];
    }

    public static function migrateData()
    {
        $query = static::find()->andWhere(['Farms_HideFlag' => 0, 'Farms_Owner' => '24180001']);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(Constants::ORG_NAME);
        $model = new Farm(['country_id' => $countryId, 'org_id' => $orgId]);
        foreach ($query->batch() as $i => $dataModels) {
            foreach ($dataModels as $dataModel) {
                $newModel = clone $model;
                $newModel->migration_id = Helper::getMigrationId($dataModel->Farms_ID);
                //$newModel->client_id = self::getClientId($dataModel->Farms_Owner);
                $newModel->code = $dataModel->Farms_SHNo;
                $newModel->farm_postal_address = $dataModel->Farms_Address;
                $newModel->latitude = $dataModel->Farms_Lat;
                $newModel->longitude = $dataModel->Farms_Long;
                $newModel->farm_location = $dataModel->Farms_Location;
                $newModel->farm_town = $dataModel->Farms_Flags1;
                $newModel->farm_country = $dataModel->Farms_Flags2;
                $newModel->farmer_name = $newModel->client->contact_person ?? null;
                $newModel->name = $newModel->client->name ?? null;
                if (empty($newModel->farmer_name)) {
                    $newModel->farmer_name = $newModel->code;
                }
                if (empty($newModel->name)) {
                    $newModel->name = $newModel->code;
                }
                static::saveModel($newModel, $n);
                $n++;
            }
        }
    }

    /**
     * @param string $oldClientId
     * @return string|null
     * @throws \Exception
     */
    public static function getClientId($oldClientId)
    {
        $migrationId = Helper::getMigrationId($oldClientId);

        $clientId = Client::getScalar('id', ['migration_id' => $migrationId]);
        if (empty($clientId)) {
            return null;
        }
        return $clientId;
    }
}