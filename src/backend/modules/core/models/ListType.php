<?php

namespace backend\modules\core\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_master_list_type".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $is_active
 * @property string $created_at
 * @property int $created_by
 * @property int $is_deleted
 * @property string $deleted_at
 * @property int $deleted_by
 *
 * @property LookupList[] $lookupLists
 */
class ListType extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    const LIST_TYPE_PROJECT = 1;
    const LIST_TYPE_FARM_TYPE = 2;
    const LIST_TYPE_GENDER = 3;
    const LIST_TYPE_PERSON_AGE_GROUP = 4;
    const LIST_TYPE_ROSTER_EDUCATION_LEVEL = 5;
    const LIST_TYPE_LANGUAGE = 6;
    const LIST_TYPE_PERSON_RELATIONSHIP = 7;
    const LIST_TYPE_ANIMAL_BREEDS = 8;
    const LIST_TYPE_COW_TYPE = 9;
    const LIST_TYPE_ANIMAL_APPROXIMATE_AGE = 10;
    const LIST_TYPE_CALVE_DEFORMITY = 11;
    const LIST_TYPE_UDDER_SCORE = 12;
    const LIST_TYPE_SIRE_TYPE = 13;
    const LIST_TYPE_BREED_COMPOSITION = 14;
    const LIST_TYPE_CALVING_METHOD = 15;
    const LIST_TYPE_CALVING_TYPE = 16;
    const LIST_TYPE_SIRE_OWNER = 17;
    const LIST_TYPE_AI_PROVIDER = 18;
    const LIST_TYPE_EASE_OF_CALVING = 19;
    const LIST_TYPE_BIRTH_TYPE = 20;
    const LIST_TYPE_CALVE_USE = 21;
    const LIST_TYPE_CALVE_STATUS = 22;
    const LIST_TYPE_WHY_DEAD = 23;
    const LIST_TYPE_FEED_METHOD = 24;
    const LIST_TYPE_LAND_TENURE_SYSTEM = 25;
    const LIST_TYPE_LAND_PARCEL_USE = 26;
    const LIST_TYPE_LAND_PARCEL_OWNER = 27;
    const LIST_TYPE_WATER_MAIN_SOURCE = 28;
    const LIST_TYPE_WATER_TRANSPORT_MODE = 29;
    const LIST_TYPE_WHO_TRANSPORTS_WATER = 30;
    const LIST_TYPE_WATER_CONSTRAINTS = 31;
    const LIST_TYPE_ANIMAL_SPECIES = 32;
    const LIST_TYPE_CATTLE_PURPOSE = 33;
    const LIST_TYPE_CATTLE_CATEGORY = 34;
    const LIST_TYPE_DAIRY_HOUSEHOLD_PROBLEMS = 35;
    const LIST_TYPE_GROUP_TYPES = 36;
    const LIST_TYPE_GROUP_FUNCTIONS = 37;
    const LIST_TYPE_HOUSING_TYPE = 38;
    const LIST_TYPE_HOUSING_MATERIALS = 39;
    const LIST_TYPE_CATTLE_STRUCTURES = 40;
    const LIST_TYPE_HOUSING_STRUCTURES = 41;
    const LIST_TYPE_BREED_METHOD = 42;
    const LIST_TYPE_TECHNOLOGY_USE_REASON = 43;
    const LIST_TYPE_TECHNOLOGY_DECISION = 44;
    const LIST_TYPE_TECHNOLOGY_NO_BULL_USE_REASON = 45;
    const LIST_TYPE_AI_PROVIDER_TYPES = 46;
    const LIST_TYPE_HEALTH_PROVIDER = 47;
    const LIST_TYPE_HEALTH_DECISION = 48;
    const LIST_TYPE_HEALTH_DISEASE = 49;
    const LIST_TYPE_HEALTH_METHOD = 50;
    const LIST_TYPE_FEEDING_SYSTEM = 51;
    const LIST_TYPE_FODDER_SPECIES = 52;
    const LIST_TYPE_PROPORTION = 53;
    const LIST_TYPE_FODDER_INFORMATION = 54;
    const LIST_TYPE_GRAIN_SOURCE = 55;
    const LIST_TYPE_RESIDUE_TYPES = 56;
    const LIST_TYPE_RESIDUE_SOURCE = 57;
    const LIST_TYPE_RESIDUE_PURCHASE = 58;
    const LIST_TYPE_CONCENTRATE_TYPES = 59;
    const LIST_TYPE_CONCENTRATE_PURCHASE = 60;
    const LIST_TYPE_CONCENTRATE_INFORMATION = 61;
    const LIST_TYPE_ANIMAL_TYPES = 62;
    const LIST_TYPE_ANIMAL_EAR_TAG = 63;
    const LIST_TYPE_ANIMAL_KNOWN_DATE_OF_BIRTH = 64;
    const LIST_TYPE_AITECH_TYPE = 65;
    const LIST_TYPE_EDUCATION_LEVEL = 66;
    const LIST_TYPE_YESNO = 67;
    const LIST_TYPE_ADMIN_AREAS = 68;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_master_list_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id'], 'integer', 'min' => 1],
            [['is_active'], 'safe'],
            [['name'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 255],
            [['id', 'name'], 'unique', 'message' => Lang::t('{attribute} already exists.')],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLookupLists()
    {
        return $this->hasMany(LookupList::class, ['list_type_id' => 'id']);
    }

    /**
     *  {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            'is_active',
            'id',
        ];
    }
}
