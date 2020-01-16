<?php

namespace backend\modules\core\models;

use backend\modules\auth\models\Users;
use backend\modules\conf\models\NumberingFormat;
use backend\modules\conf\settings\CountryAdministrativeUnits;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "organization".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $country
 * @property string $contact_person
 * @property string $contact_phone
 * @property string $contact_email
 * @property int $is_active
 * @property string $uuid
 * @property string $unit1_name
 * @property string $unit2_name
 * @property string $unit3_name
 * @property string $unit4_name
 * @property string $dialing_code
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Users[] $authUsers
 * @property OrganizationUnits[] $organizationUnits
 */
class Organization extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    const NUMBERING_FORMAT_ID = 'organization_account_no';

    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->unit1_name = CountryAdministrativeUnits::getUnit1Name();
            $this->unit2_name = CountryAdministrativeUnits::getUnit2Name();
            $this->unit3_name = CountryAdministrativeUnits::getUnit3Name();
            $this->unit4_name = CountryAdministrativeUnits::getUnit4Name();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%organization}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country', 'unit1_name', 'unit2_name', 'unit3_name', 'unit4_name', 'dialing_code'], 'required'],
            [['is_active'], 'safe'],
            [['code'], 'string', 'max' => 128],
            [['name', 'contact_email', 'uuid'], 'string', 'max' => 255],
            [['dialing_code'], 'string', 'min' => 3, 'max' => 3],
            [['dialing_code'], 'number', 'min' => 0],
            [['country'], 'string', 'max' => 3],
            [['country'], 'unique'],
            [['contact_person', 'unit1_name', 'unit2_name', 'unit3_name', 'unit4_name'], 'string', 'max' => 30],
            [['contact_phone'], 'string', 'min' => 8, 'max' => 20],
            ['code', 'unique'],
            ['contact_email', 'email'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'ODK Code',
            'name' => 'Country Name',
            'country' => 'Country',
            'contact_person' => 'Contact Person',
            'contact_phone' => 'Contact Phone',
            'contact_email' => 'Contact Email',
            'is_active' => 'Active',
            'uuid' => 'Uuid',
            'unit1_name' => 'Region Level Name',
            'unit2_name' => 'District Level Name',
            'unit3_name' => 'Ward Level Name',
            'unit4_name' => 'Village Level Name',
            'dialing_code'=>'Dialing Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::class, ['org_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizationUnits()
    {
        return $this->hasMany(OrganizationUnits::class, ['org_id' => 'id']);
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['code', 'code'],
            ['name', 'name'],
            ['country', 'country'],
            'is_active',
            'dialing_code',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->code)) {
                $this->code = NumberingFormat::getNextFormattedNumber(self::NUMBERING_FORMAT_ID);
            }
            $this->name = Country::getScalar('name', ['iso2' => $this->country]);
            return true;
        }
        return false;
    }
}
