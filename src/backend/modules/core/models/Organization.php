<?php

namespace backend\modules\core\models;

use backend\modules\auth\models\Users;
use backend\modules\conf\models\NumberingFormat;
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
            [['name', 'country', 'contact_phone', 'contact_email', 'unit1_name', 'unit2_name', 'unit3_name', 'unit4_name'], 'required'],
            [['is_active'], 'safe'],
            [['code'], 'string', 'max' => 128],
            [['name', 'contact_email', 'uuid'], 'string', 'max' => 255],
            [['country'], 'string', 'max' => 3],
            [['country'], 'unique'],
            [['contact_person', 'unit1_name', 'unit2_name', 'unit3_name', 'unit4_name'], 'string', 'max' => 30],
            [['contact_phone'], 'string', 'min' => 8, 'max' => 20],
            ['code', 'unique'],
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
            'code' => 'Code',
            'name' => 'Name',
            'country' => 'Country',
            'contact_person' => 'Contact Person',
            'contact_phone' => 'Contact Phone',
            'contact_email' => 'Contact Email',
            'is_active' => 'Active',
            'uuid' => 'Uuid',
            'unit1_name' => 'Unit 1 Name',
            'unit2_name' => 'Unit 2 Name',
            'unit3_name' => 'Unit 3 Name',
            'unit4_name' => 'Unit 4 Name',
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
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->code)) {
                $this->code = NumberingFormat::getNextFormattedNumber(self::NUMBERING_FORMAT_ID);
            }

            return true;
        }
        return false;
    }
}
