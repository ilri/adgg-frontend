<?php

namespace backend\modules\core\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use yii\helpers\Html;

/**
 * This is the model class for table "core_client".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $org_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property int $farm_id
 * @property string $phone
 * @property string $email
 * @property int $field_agent_id
 * @property int $is_head
 * @property string $gender_code
 * @property int $is_active
 * @property string $uuid
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property int $is_deleted
 * @property string $deleted_at
 * @property int $deleted_by
 *
 * @property Organization $org
 * @property Farm $farm
 */
class Client extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait, OrganizationUnitDataTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_client}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'org_id', 'region_id', 'district_id', 'ward_id', 'farm'], 'required'],
            [['org_id', 'region_id', 'district_id', 'ward_id', 'village_id', 'field_agent_id', 'is_head', 'is_active',], 'integer'],
            [['code', 'name'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 255],
            [['gender_code'], 'string', 'max' => 10],
            [['email'], 'email'],
            [['org_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['org_id' => 'id']],
            [['name'], 'unique', 'targetAttribute' => ['farm_id', 'name'], 'message' => Lang::t('{attribute} already exists.')],
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
            'org_id' => 'Country',
            'farm_id'=>'Farm',
            'region_id' => $this->org !== null ? Html::encode($this->org->unit1_name) : 'Region',
            'district_id' => $this->org !== null ? Html::encode($this->org->unit2_name) : 'District',
            'ward_id' => $this->org !== null ? Html::encode($this->org->unit3_name) : 'Ward',
            'village_id' => $this->org !== null ? Html::encode($this->org->unit4_name) : 'Village',
            'phone' => 'Phone No.',
            'email' => 'Email',
            'field_agent_id' => 'Field Agent',
            'is_head' => 'Is Household Head',
            'gender_code' => 'Gender',
            'is_active' => 'Active',
            'uuid' => 'Uuid',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['code', 'code'],
            ['name', 'name'],
            ['phone', 'phone'],
            'org_id',
            'region_id',
            'district_id',
            'ward_id',
            'village_id',
            'field_agent_id',
            'is_head',
            'gender_code',
            'is_active',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFarm()
    {
        return $this->hasOne(Farm::class, ['id' => 'farm_id']);
    }
}
