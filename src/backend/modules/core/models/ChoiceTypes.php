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
 * @property Choices[] $lookupLists
 */
class ChoiceTypes extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    const CHOICE_TYPE_PROJECT = 1;
    const CHOICE_TYPE_FARM_TYPE = 2;
    const CHOICE_TYPE_GENDER = 3;
    const CHOICE_TYPE_PERSON_AGE_GROUP = 4;
    const CHOICE_TYPE_ROSTER_EDUCATION_LEVEL = 5;
    const CHOICE_TYPE_LANGUAGE = 6;
    const CHOICE_TYPE_PERSON_RELATIONSHIP = 7;
    const CHOICE_TYPE_ANIMAL_BREEDS = 8;
    const CHOICE_TYPE_COW_TYPE = 9;
    const CHOICE_TYPE_ANIMAL_APPROXIMATE_AGE = 10;
    const CHOICE_TYPE_CALVE_DEFORMITY = 11;
    const CHOICE_TYPE_UDDER_SCORE = 12;
    const CHOICE_TYPE_SIRE_TYPE = 13;
    const CHOICE_TYPE_BREED_COMPOSITION = 14;
    const CHOICE_TYPE_CALVING_METHOD = 15;
    const CHOICE_TYPE_CALVING_TYPE = 16;
    const CHOICE_TYPE_SIRE_OWNER = 17;
    const CHOICE_TYPE_AI_PROVIDER = 18;
    const CHOICE_TYPE_EASE_OF_CALVING = 19;
    const CHOICE_TYPE_BIRTH_TYPE = 20;
    const CHOICE_TYPE_CALVE_USE = 21;
    const CHOICE_TYPE_CALVE_STATUS = 22;
    const CHOICE_TYPE_WHY_DEAD = 23;
    const CHOICE_TYPE_FEED_METHOD = 24;
    const CHOICE_TYPE_LAND_TENURE_SYSTEM = 25;
    const CHOICE_TYPE_LAND_PARCEL_USE = 26;
    const CHOICE_TYPE_LAND_PARCEL_OWNER = 27;
    const CHOICE_TYPE_WATER_MAIN_SOURCE = 28;
    const CHOICE_TYPE_WATER_TRANSPORT_MODE = 29;
    const CHOICE_TYPE_WHO_TRANSPORTS_WATER = 30;
    const CHOICE_TYPE_WATER_CONSTRAINTS = 31;
    const CHOICE_TYPE_ANIMAL_SPECIES = 32;
    const CHOICE_TYPE_CATTLE_PURPOSE = 33;
    const CHOICE_TYPE_CATTLE_CATEGORY = 34;
    const CHOICE_TYPE_DAIRY_HOUSEHOLD_PROBLEMS = 35;
    const CHOICE_TYPE_GROUP_TYPES = 36;
    const CHOICE_TYPE_GROUP_FUNCTIONS = 37;
    const CHOICE_TYPE_HOUSING_TYPE = 38;
    const CHOICE_TYPE_HOUSING_MATERIALS = 39;
    const CHOICE_TYPE_CATTLE_STRUCTURES = 40;
    const CHOICE_TYPE_HOUSING_STRUCTURES = 41;
    const CHOICE_TYPE_BREED_METHOD = 42;
    const CHOICE_TYPE_TECHNOLOGY_USE_REASON = 43;
    const CHOICE_TYPE_TECHNOLOGY_DECISION = 44;
    const CHOICE_TYPE_TECHNOLOGY_NO_BULL_USE_REASON = 45;
    const CHOICE_TYPE_AI_PROVIDER_TYPES = 46;
    const CHOICE_TYPE_HEALTH_PROVIDER = 47;
    const CHOICE_TYPE_HEALTH_DECISION = 48;
    const CHOICE_TYPE_HEALTH_DISEASE = 49;
    const CHOICE_TYPE_HEALTH_METHOD = 50;
    const CHOICE_TYPE_FEEDING_SYSTEM = 51;
    const CHOICE_TYPE_FODDER_SPECIES = 52;
    const CHOICE_TYPE_PROPORTION = 53;
    const CHOICE_TYPE_FODDER_INFORMATION = 54;
    const CHOICE_TYPE_GRAIN_SOURCE = 55;
    const CHOICE_TYPE_RESIDUE_TYPES = 56;
    const CHOICE_TYPE_RESIDUE_SOURCE = 57;
    const CHOICE_TYPE_RESIDUE_PURCHASE = 58;
    const CHOICE_TYPE_CONCENTRATE_TYPES = 59;
    const CHOICE_TYPE_CONCENTRATE_PURCHASE = 60;
    const CHOICE_TYPE_CONCENTRATE_INFORMATION = 61;
    const CHOICE_TYPE_ANIMAL_TYPES = 62;
    const CHOICE_TYPE_ANIMAL_EAR_TAG = 63;
    const CHOICE_TYPE_ANIMAL_KNOWN_DATE_OF_BIRTH = 64;
    const CHOICE_TYPE_AITECH_TYPE = 65;
    const CHOICE_TYPE_EDUCATION_LEVEL = 66;
    const CHOICE_TYPE_YESNO = 67;
    const CHOICE_TYPE_ADMIN_AREAS = 68;
    const CHOICE_TYPE_ANIMAL_ENTRY_TYPE = 69;
    const CHOICE_TYPE_MILK_SAMPLE_TYPE = 70;
    const CHOICE_TYPE_ANIMAL_BODY_CONDITION = 71;
    const CHOICE_TYPE_AI_TYPES = 72;
    const CHOICE_TYPE_SEMEN_TYPE = 73;

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
        return $this->hasMany(Choices::class, ['list_type_id' => 'id']);
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
