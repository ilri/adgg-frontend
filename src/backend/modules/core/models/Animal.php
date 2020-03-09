<?php

namespace backend\modules\core\models;

use backend\modules\reports\Constants;
use common\helpers\DbUtils;
use common\helpers\FileManager;
use common\helpers\Lang;
use common\helpers\Utils;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use common\models\CustomValidationsTrait;
use common\widgets\highchart\HighChart;
use common\widgets\highchart\HighChartInterface;
use Yii;
use yii\db\Expression;
use yii\helpers\Inflector;

/**
 * This is the model class for table "core_animal".
 *
 * @property int $id
 * @property string $name
 * @property string $tag_id
 * @property int $farm_id
 * @property int $herd_id
 * @property int $country_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property int $org_id
 * @property int $client_id
 * @property int $animal_type
 * @property string $color
 * @property string $birthdate
 * @property int $is_derived_birthdate
 * @property array $deformities
 * @property int $sire_type
 * @property int $sire_id
 * @property string $sire_tag_id
 * @property string $sire_name
 * @property int $dam_id
 * @property string $dam_tag_id
 * @property string $dam_name
 * @property int $main_breed
 * @property int $breed_composition
 * @property int $secondary_breed
 * @property int $entry_type
 * @property string $entry_date
 * @property string $purchase_cost
 * @property string $animal_photo
 * @property string $latitude
 * @property string $longitude
 * @property string $map_address
 * @property string $latlng
 * @property string $uuid
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property string|array $additional_attributes
 * @property string $animal_eartag_id
 * @property string $migration_id
 *
 * @property Farm $farm
 * @property Animal $sire
 * @property Animal $dam
 * @property AnimalHerd $herd
 * @property AnimalEvent [] $events
 *
 */
class Animal extends ActiveRecord implements ActiveSearchInterface, TableAttributeInterface, UploadExcelInterface, HighChartInterface
{
    use ActiveSearchTrait, CountryUnitDataTrait, TableAttributeTrait, CustomValidationsTrait, AnimalValidators;

    const ANIMAL_TYPE_HEIFER = 1;
    const ANIMAL_TYPE_COW = 2;
    const ANIMAL_TYPE_MALE_CALF = 3;
    const ANIMAL_TYPE_FEMALE_CALF = 4;
    const ANIMAL_TYPE_BULL = 5;
    const ANIMAL_TYPE_AI_STRAW = 6;

    public $odkFarmCode;
    public $derivedBirthdate;
    /**
     * @var string
     */
    public $tmp_animal_photo;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_animal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['farm_id', 'tag_id'], 'required'],
            [['farm_id', 'herd_id', 'country_id', 'region_id', 'district_id', 'ward_id', 'village_id', 'animal_type', 'is_derived_birthdate', 'sire_type', 'sire_id', 'dam_id', 'main_breed', 'breed_composition', 'secondary_breed', 'entry_type'], 'integer'],
            [['birthdate', 'deformities', 'entry_date'], 'safe'],
            [['purchase_cost'], 'number'],
            [['birthdate', 'entry_date'], 'validateNoFutureDate'],
            [['name', 'tag_id', 'sire_tag_id', 'sire_name', 'dam_tag_id', 'dam_name', 'color'], 'string', 'max' => 128],
            [['animal_photo', 'map_address'], 'string', 'max' => 255],
            [['farm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Farm::class, 'targetAttribute' => ['farm_id' => 'id']],
            ['tag_id', 'unique', 'targetAttribute' => ['country_id', 'tag_id'], 'message' => '{attribute} already exists.'],
            [['sire_tag_id', 'dam_tag_id'], 'validateSireOrDam'],
            ['sire_tag_id', 'validateSireBisexual'],
            ['dam_tag_id', 'validateDamBisexual'],
            [['tmp_animal_photo', 'additional_attributes', 'org_id', 'client_id'], 'safe'],
            [$this->getAdditionalAttributes(), 'safe'],
            [$this->getExcelColumns(), 'safe', 'on' => self::SCENARIO_UPLOAD],
            ['migration_id', 'unique'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = [
            'id' => 'ID',
            'name' => 'Animal Name',
            'tag_id' => 'Animal Tag ID',
            'farm_id' => 'Farm ID',
            'herd_id' => 'Herd ID',
            'country_id' => 'Country ID',
            'region_id' => 'Region ID',
            'district_id' => 'District ID',
            'ward_id' => 'Ward ID',
            'village_id' => 'Village ID',
            'org_id' => 'External Organization ID',
            'client_id' => 'Client ID',
            'animal_type' => 'Animal Type',
            'color' => 'Animal Color',
            'birthdate' => 'Date of Birth',
            'is_derived_birthdate' => 'Is Derived Birthdate',
            'deformities' => 'Deformities',
            'sire_type' => 'Sire Type',
            'sire_id' => 'Sire ID',
            'sire_tag_id' => 'Sire Tag ID',
            'sire_name' => 'Sire Name',
            'bull_straw_id' => 'Bull Straw ID',
            'dam_id' => 'Dam ID',
            'dam_tag_id' => 'Dam Tag ID',
            'dam_name' => 'Dam Name',
            'main_breed' => 'Main Breed',
            'breed_composition' => 'Breed Composition',
            'secondary_breed' => 'Secondary Breed',
            'entry_type' => 'Entry Type',
            'entry_date' => 'Entry Date',
            'purchase_cost' => 'Purchase Cost',
            'animal_photo' => 'Animal Photo',
            'tmp_animal_photo' => 'Animal Photo',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'map_address' => 'Map Address',
            'uuid' => 'Uuid',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'odkFarmCode' => 'Farm Code',
            'derivedBirthdate' => 'Derived Birthdate',
        ];

        return array_merge($labels, $this->getOtherAttributeLabels());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFarm()
    {
        return $this->hasOne(Farm::class, ['id' => 'farm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHerd()
    {
        return $this->hasOne(AnimalHerd::class, ['id' => 'herd_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSire()
    {
        return $this->hasOne(Animal::class, ['id' => 'sire_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDam()
    {
        return $this->hasOne(Animal::class, ['id' => 'dam_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(AnimalEvent::class, ['animal_id' => 'id']);
    }

    /**
     *  {@inheritDoc}
     */
    public function searchParams()
    {
        $alias = static::tableName();
        return [
            [$alias . '.tag_id', 'tag_id'],
            [$alias . '.name', 'name'],
            [$alias . '.color', 'color'],
            [$alias . '.sire_name', 'sire_name'],
            [$alias . '.sire_tag_id', 'sire_tag_id'],
            [$alias . '.dam_tag_id', 'dam_tag_id'],
            [$alias . '.dam_name', 'dam_name'],
            [$alias . '.farm_id', 'farm_id', '', '='],
            [$alias . '.country_id', 'country_id', '', '='],
            [$alias . '.region_id', 'region_id', '', '='],
            [$alias . '.district_id', 'district_id', '', '='],
            [$alias . '.ward_id', 'ward_id', '', '='],
            [$alias . '.village_id', 'village_id', '', '='],
            [$alias . '.org_id', 'org_id', '', '='],
            [$alias . '.client_id', 'client_id', '', '='],
            [$alias . '.animal_type', 'animal_type', '', '='],
            [$alias . '.dam_id', 'dam_id', '', '='],
            [$alias . '.sire_id', 'sire_id', '', '='],
            [$alias . '.herd_id', 'herd_id', '', '='],
            [$alias . '.entry_type', 'entry_type', '', '='],
            [$alias . '.main_breed', 'main_breed', '', '='],
        ];
    }

    public function fields()
    {
        $fields = $this->apiResourceFields();
        $fields['animal_type'] = function () {
            return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, $this->animal_type);
        };
        /**
         * @return string
         */
        $fields['sire_type'] = function () {
            return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_SIRE_TYPE, $this->sire_type);

        };
        $fields['breed_composition'] = function () {
            return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_BREED_COMPOSITION, $this->breed_composition);
        };
        $fields['main_breed'] = function () {
            return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, $this->main_breed);
        };
        $fields['secondary_breed'] = function () {
            return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, $this->secondary_breed);
        };
        $fields['entry_type'] = function () {
            return Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_ENTRY_TYPE, $this->entry_type);
        };
        /**
         * @return array
         */
        $fields['deformities'] = function () {
            $decoded = [];
            foreach ($this->deformities as $key => $value) {
                $decoded[] = Choices::getLabel(ChoiceTypes::CHOICE_TYPE_CALVE_DEFORMITY, $value);
            };
            return $decoded;
        };
        $fields['is_derived_birthdate'] = function () {
            return Utils::decodeBoolean($this->is_derived_birthdate);
        };
        return $fields;
    }

    public static function decodeDeformities($deformities){
        $deformities = json_decode($deformities);
        $decoded = [];
        foreach ($deformities as $key => $value) {
            $decoded[] = Choices::getLabel(ChoiceTypes::CHOICE_TYPE_CALVE_DEFORMITY, $value);
        };
        return implode(',', $decoded);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ignoreAdditionalAttributes = true;
            if (!empty($this->latitude) && !empty($this->longitude)) {
                $this->latlng = new Expression("ST_GeomFromText('POINT({$this->latitude} {$this->longitude})')");
            }
            $this->setImage('animal_photo');
            $this->country_id = $this->farm->country_id;
            $this->region_id = $this->farm->region_id;
            $this->district_id = $this->farm->district_id;
            $this->ward_id = $this->farm->ward_id;
            $this->village_id = $this->farm->village_id;
            $this->org_id = $this->farm->org_id;
            $this->client_id = $this->farm->client_id;
            if (!empty($this->deformities)) {
                if (is_string($this->deformities)) {
                    $this->deformities = array_map('trim', explode(' ', $this->deformities));
                }
            } else {
                $this->deformities = [];
            }
            if (empty($this->birthdate) && !empty($this->derivedBirthdate)) {
                $this->birthdate = $this->derivedBirthdate;
                $this->is_derived_birthdate = 1;
            }

            $this->setAdditionalAttributesValues();

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAdditionalAttributeValues();
    }

    /**
     * @return int
     */
    public static function getDefinedTableId(): int
    {
        return ExtendableTable::TABLE_ANIMAL_ATTRIBUTES;
    }

    /**
     * @return int
     */
    public static function getDefinedType(): int
    {
        return TableAttribute::TYPE_ATTRIBUTE;
    }

    /**
     *
     * @return string
     */
    public function getDir()
    {
        return FileManager::createDir($this->getBaseDir() . DIRECTORY_SEPARATOR . $this->uuid);
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return FileManager::createDir(FileManager::getUploadsDir() . DIRECTORY_SEPARATOR . 'animals');
    }

    protected function setImage(string $imageAttribute)
    {
        $tmpField = 'tmp_' . $imageAttribute;
        if (empty($this->{$tmpField}))
            return false;

        $ext = $ext = pathinfo($this->{$tmpField}, PATHINFO_EXTENSION);
        $file_name = $imageAttribute . '.' . $ext;
        $temp_dir = dirname($this->{$tmpField});
        $new_path = $this->getDir() . DIRECTORY_SEPARATOR . $file_name;
        if (copy($this->{$tmpField}, $new_path)) {
            $this->{$imageAttribute} = $file_name;
            $this->{$tmpField} = null;

            if (!empty($temp_dir)) {
                FileManager::deleteDirOrFile($temp_dir);
            }
        }
    }

    /**
     * @param string $imageAttribute
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getImageUrl(string $imageAttribute)
    {
        $file_path = $this->getImagePath($imageAttribute);
        if (empty($file_path)) {
            return null;
        }
        $asset = Yii::$app->getAssetManager()->publish($file_path);

        return $asset[1];
    }

    /**
     * @param string $imageAttribute
     * @return null|string
     */
    public function getImagePath(string $imageAttribute)
    {
        $path = null;
        if (empty($this->{$imageAttribute}))
            return null;

        $file = $this->getDir() . DIRECTORY_SEPARATOR . $this->{$imageAttribute};
        if (file_exists($file)) {
            $path = $file;
        }

        return $path;
    }

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        //$columns = array_merge($this->safeAttributes(), $this->getAdditionalAttributes());
        return [
            'odkFarmCode',
            'tag_id',
            'animal_tagprefix',
            'animal_tagsequence',
            'name',
            'color',
            'derivedBirthdate',
            'birthdate',
            'animal_approxage',
            'deformities',
            'sire_type',
            'animal_sireknown',
            'sire_tag_id',
            'sire_name',
            'animal_damknown',
            'dam_tag_id',
            'dam_name',
            'main_breed',
            'breed_composition',
            'secondary_breed',
            'entry_type',
            'entry_date',
            'purchase_cost',
            'animal_photo',
            'latitude',
            'longitude',
            'altitude',
            'gprs_accuracy',
            'animal_type',
        ];
    }

    /**
     * @param $attribute
     * @param $listTypeId
     * @return string|null
     * @throws \Exception
     */
    public function getListValueLabel($attribute, $listTypeId)
    {
        $label = Choices::getScalar('label', ['list_type_id' => $listTypeId, 'value' => $this->{$attribute}]);
        if ($label) {
            return $label;
        }
        return null;
    }

    /**
     * @param integer $durationType
     * @param bool|string $sum
     * @param array $filters array key=>$value pair where key is the attribute name and value is the attribute value
     * @param string $dateField
     * @param null|string $from
     * @param null|string $to
     * @param mixed $condition
     * @param array $params
     * @return int
     * @throws \Exception
     */
    public static function getDashboardStats($durationType, $sum = false, $filters = [], $dateField = 'created_at', $from = null, $to = null, $condition = '', $params = [])
    {
        list($condition, $params) = static::appendOrgSessionIdCondition($condition, $params, false);

        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }

    /**
     *  {@inheritDoc}
     */
    public static function highChartOptions($graphType, $queryOptions)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = static::appendOrgSessionIdCondition($condition, $params, false);
        $groupBy = $queryOptions['groupBy'] ?? array_key_first(Constants::animalGraphGroupByOptions(false));
        $breed = $queryOptions['filters']['main_breed'] ?? null;
        $animalType = $queryOptions['filters']['animal_type'] ?? null;
        $series = [];
        if ($graphType == HighChart::GRAPH_PIE && empty($breed) && empty($animalType)) {
            if ($groupBy == Constants::ANIMAL_GRAPH_GROUP_BY_BREEDS) {
                $breeds = Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
                foreach ($breeds as $breed => $label) {
                    list($newCondition, $newParams) = DbUtils::appendCondition('main_breed', $breed, $condition, $params);
                    $series[] = [
                        'name' => Lang::t('{breed}', ['breed' => $label]),
                        'condition' => $newCondition,
                        'params' => $newParams,
                        'sum' => false,
                    ];
                }
            } else {
                $animalTypes = Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES);
                foreach ($animalTypes as $animalType => $label) {
                    list($newCondition, $newParams) = DbUtils::appendCondition('animal_type', $animalType, $condition, $params);
                    $series[] = [
                        'name' => Lang::t('{animalType}', ['animalType' => Inflector::pluralize($label)]),
                        'condition' => $newCondition,
                        'params' => $newParams,
                        'sum' => false,
                    ];
                }
            }
        } else {
            if (!empty($breed) && !empty($animalType)) {
                $nameTemplate = '{breed}, {animal_type}';
            } elseif (!empty($breed)) {
                $nameTemplate = '{breed}';
            } elseif (!empty($animalType)) {
                $nameTemplate = '{animal_type}';
            } else {
                $nameTemplate = 'Animals';
            }
            $name = strtr($nameTemplate, [
                '{breed}' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, $breed),
                '{animal_type}' => Choices::getLabel(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, $animalType),
            ]);
            $series = [
                [
                    'name' => $name,
                    'condition' => $condition,
                    'params' => $params,
                    'sum' => false,
                ],
            ];
        }
        if ($graphType !== HighChart::GRAPH_PIE) {
            return $series;
        } else {
            return [
                [
                    'data' => $series,
                ]
            ];
        }
    }

    public static function getListData($valueColumn = 'id', $textColumn = 'name', $prompt = false, $condition = '', $params = [], $options = [])
    {
        $options['orderBy'] = ['id' => SORT_ASC];
        return parent::getListData($valueColumn, $textColumn, $prompt, $condition, $params, $options);
    }
}
