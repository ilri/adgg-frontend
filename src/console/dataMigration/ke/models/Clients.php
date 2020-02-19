<?php

namespace console\dataMigration\ke\models;

use backend\modules\core\models\Client;
use common\models\ActiveRecord;
use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property float $Clients_ID
 * @property string $Clients_PersonName
 * @property string $Clients_BusName
 * @property string|null $Clients_Greeting
 * @property string|null $Clients_Address1
 * @property string|null $Clients_Address2
 * @property string|null $Clients_Address3
 * @property int|null $Clients_Postcode
 * @property string|null $Clients_SHNo
 * @property string|null $Clients_Phone
 * @property string|null $Clients_Mobile
 * @property string|null $Clients_Fax
 * @property string|null $Clients_Email
 * @property string|null $Clients_FactDeduct
 * @property string|null $Clients_FactAcctNo
 * @property string|null $Clients_TaxNo
 * @property string|null $Clients_AccountKey
 * @property string|null $Clients_AccountGroup
 * @property string $Clients_Suspend
 * @property string|null $Clients_Flags1
 * @property string|null $Clients_Flags2
 * @property float|null $Clients_Branch
 * @property string|null $Clients_Remark
 * @property float|null $Clients_ShareHolder
 * @property int $Clients_Upload
 * @property int $Clients_Download
 * @property string|null $Clients_Modified
 * @property string|null $Clients_ModifiedBy
 * @property int $Clients_HideFlag
 * @property int $Clients_Locked
 */
class Clients extends ActiveRecord implements MigrationInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%clients}}';
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
            [['Clients_ID', 'Clients_PersonName', 'Clients_BusName'], 'required'],
            [['Clients_ID', 'Clients_Branch', 'Clients_ShareHolder'], 'number'],
            [['Clients_Postcode', 'Clients_Upload', 'Clients_Download', 'Clients_HideFlag', 'Clients_Locked'], 'integer'],
            [['Clients_Modified'], 'safe'],
            [['Clients_PersonName', 'Clients_BusName', 'Clients_Email'], 'string', 'max' => 50],
            [['Clients_Greeting', 'Clients_Address1', 'Clients_Address2', 'Clients_Address3', 'Clients_Remark'], 'string', 'max' => 35],
            [['Clients_SHNo'], 'string', 'max' => 6],
            [['Clients_Phone', 'Clients_Mobile', 'Clients_Fax'], 'string', 'max' => 15],
            [['Clients_FactDeduct', 'Clients_Suspend'], 'string', 'max' => 1],
            [['Clients_FactAcctNo', 'Clients_TaxNo', 'Clients_AccountKey'], 'string', 'max' => 20],
            [['Clients_AccountGroup', 'Clients_Flags1', 'Clients_Flags2', 'Clients_ModifiedBy'], 'string', 'max' => 10],
            [['Clients_PersonName', 'Clients_HideFlag'], 'unique', 'targetAttribute' => ['Clients_PersonName', 'Clients_HideFlag']],
            [['Clients_BusName', 'Clients_HideFlag'], 'unique', 'targetAttribute' => ['Clients_BusName', 'Clients_HideFlag']],
            [['Clients_SHNo', 'Clients_PersonName', 'Clients_HideFlag'], 'unique', 'targetAttribute' => ['Clients_SHNo', 'Clients_PersonName', 'Clients_HideFlag']],
            [['Clients_ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Clients_ID' => 'Clients ID',
            'Clients_PersonName' => 'Clients Person Name',
            'Clients_BusName' => 'Clients Bus Name',
            'Clients_Greeting' => 'Clients Greeting',
            'Clients_Address1' => 'Clients Address1',
            'Clients_Address2' => 'Clients Address2',
            'Clients_Address3' => 'Clients Address3',
            'Clients_Postcode' => 'Clients Postcode',
            'Clients_SHNo' => 'Clients Sh No',
            'Clients_Phone' => 'Clients Phone',
            'Clients_Mobile' => 'Clients Mobile',
            'Clients_Fax' => 'Clients Fax',
            'Clients_Email' => 'Clients Email',
            'Clients_FactDeduct' => 'Clients Fact Deduct',
            'Clients_FactAcctNo' => 'Clients Fact Acct No',
            'Clients_TaxNo' => 'Clients Tax No',
            'Clients_AccountKey' => 'Clients Account Key',
            'Clients_AccountGroup' => 'Clients Account Group',
            'Clients_Suspend' => 'Clients Suspend',
            'Clients_Flags1' => 'Clients Flags1',
            'Clients_Flags2' => 'Clients Flags2',
            'Clients_Branch' => 'Clients Branch',
            'Clients_Remark' => 'Clients Remark',
            'Clients_ShareHolder' => 'Clients Share Holder',
            'Clients_Upload' => 'Clients Upload',
            'Clients_Download' => 'Clients Download',
            'Clients_Modified' => 'Clients Modified',
            'Clients_ModifiedBy' => 'Clients Modified By',
            'Clients_HideFlag' => 'Clients Hide Flag',
            'Clients_Locked' => 'Clients Locked',
        ];
    }

    public static function migrateData()
    {
        $query = static::find()->andWhere(['Clients_HideFlag' => 0]);
        /* @var $models $this[] */
        $n = 1;
        $className = static::class;
        $countryId = Helper::getCountryId(Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(Constants::KLBA_ORG_NAME);
        $clientModel = new Client(['country_id' => $countryId, 'org_id' => $orgId]);
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $newClientModel = clone $clientModel;
                $newClientModel->migration_id = Helper::getMigrationId($model->Clients_ID, Constants::KLBA_DATA_SOURCE_PREFIX);
                $newClientModel->name = !empty(trim($model->Clients_BusName)) ? $model->Clients_BusName : $model->Clients_PersonName;
                $newClientModel->contact_person = $model->Clients_PersonName;
                $newClientModel->postal_address = $model->Clients_Address1;
                $newClientModel->town = $model->Clients_Address2;
                $newClientModel->client_country = $model->Clients_Address3;
                $newClientModel->phone1 = $model->Clients_Mobile;
                $newClientModel->phone2 = $model->Clients_Phone;
                $newClientModel->email = $model->Clients_Email;
                $newClientModel->county = $model->Clients_Branch;
                $newClientModel->remarks = $model->Clients_Remark;
                $saved = $newClientModel->save();

                if ($saved) {
                    Yii::$app->controller->stdout($className . ": saved record {$n} successfully\n");
                } else {
                    Yii::$app->controller->stdout($className . ": Failed to save record {$n}\n");
                }
                $n++;
            }
        }
    }
}
