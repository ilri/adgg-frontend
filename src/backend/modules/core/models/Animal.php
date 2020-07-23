<?php

namespace backend\modules\core\models;

use backend\modules\reports\Constants;
use common\helpers\DateUtils;
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
 * @property int $sex
 * @property string|array $color
 * @property string $color_other
 * @property string $birthdate
 * @property string $reg_date
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
 * @property array|string $secondary_breed
 * @property string $secondary_breed_other
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
 * @property string $hair_sample_id
 * @property string $animal_eartag_id
 * @property string $migration_id
 * @property string $breed_composition_details
 * @property string $odk_animal_code
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


    const SCENARIO_MISTRO_DB_BULL_UPLOAD = 'BullUpload';
    const SCENARIO_MISTRO_DB_COW_UPLOAD = 'CowUpload';

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
            [['tag_id'], 'required'],
            [['farm_id'], 'required', 'except' => self::SCENARIO_MISTRO_DB_BULL_UPLOAD],
            [['farm_id', 'herd_id', 'country_id', 'region_id', 'district_id', 'ward_id', 'village_id', 'animal_type', 'is_derived_birthdate', 'sire_type', 'sire_id', 'dam_id', 'main_breed', 'breed_composition', 'secondary_breed', 'entry_type', 'sex'], 'integer'],
            [['birthdate', 'deformities', 'entry_date', 'reg_date'], 'safe'],
            [['purchase_cost'], 'number'],
            [['birthdate', 'entry_date'], 'date', 'format' => 'php:Y-m-d'],
            [['birthdate', 'reg_date', 'entry_date'], 'validateNoFutureDate'],
            [['name', 'tag_id', 'sire_tag_id', 'dam_tag_id', 'color'], 'string', 'max' => 128],
            [['animal_photo', 'map_address'], 'string', 'max' => 255],
            ['tag_id', 'unique', 'targetAttribute' => ['country_id', 'tag_id'], 'message' => '{attribute} already exists.'],
            [['sire_tag_id', 'dam_tag_id'], 'validateSireOrDam'],
            ['sire_tag_id', 'validateSireBisexual', 'except' => [self::SCENARIO_MISTRO_DB_BULL_UPLOAD, self::SCENARIO_MISTRO_DB_COW_UPLOAD]],
            ['dam_tag_id', 'validateDamBisexual', 'except' => [self::SCENARIO_MISTRO_DB_BULL_UPLOAD, self::SCENARIO_MISTRO_DB_COW_UPLOAD]],
            [['tmp_animal_photo', 'additional_attributes', 'org_id', 'client_id', 'hair_sample_id'], 'safe'],
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
            'farm_id' => 'Farm',
            'herd_id' => 'Herd',
            'country_id' => 'Country',
            'region_id' => 'Region',
            'district_id' => 'District',
            'ward_id' => 'Ward',
            'village_id' => 'Village',
            'org_id' => 'External Organization ID',
            'client_id' => 'Client',
            'animal_type' => 'Animal Type',
            'sex' => 'Sex',
            'color' => 'Animal Color',
            'birthdate' => 'Date of Birth',
            'is_derived_birthdate' => 'Is Derived Birthdate',
            'deformities' => 'Deformities',
            'sire_type' => 'Sire Type',
            'sire_id' => 'Sire',
            'sire_tag_id' => 'Sire Tag ID',
            'dam_id' => 'Dam',
            'dam_tag_id' => 'Dam Tag ID',
            'main_breed' => 'Main Breed',
            'breed_composition' => 'Breed Composition',
            'breed_composition_details' => 'Breed Composition details',
            'secondary_breed' => 'Secondary Breed',
            'entry_type' => 'Entry Type',
            'entry_date' => 'Entry Date',
            'reg_date' => 'Registration Date',
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
            'hair_sample_id' => 'Hair Sample Id'
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
            [$alias . '.sire_tag_id', 'sire_tag_id'],
            [$alias . '.dam_tag_id', 'dam_tag_id'],
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

    public static function decodeDeformities($deformities)
    {
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
            if (null !== $this->farm) {
                $this->country_id = $this->farm->country_id;
                $this->region_id = $this->farm->region_id;
                $this->district_id = $this->farm->district_id;
                $this->ward_id = $this->farm->ward_id;
                $this->village_id = $this->farm->village_id;
                if (!empty($this->farm->org_id)) {
                    $this->org_id = $this->farm->org_id;
                }
                if (!empty($this->farm->client_id)) {
                    $this->client_id = $this->farm->client_id;
                }
            }
            if (empty($this->birthdate) && !empty($this->derivedBirthdate)) {
                $this->birthdate = $this->derivedBirthdate;
                $this->is_derived_birthdate = 1;
            }
            if (empty($this->reg_date)) {
                $this->reg_date = DateUtils::getToday();
            }

            if (!empty($this->sire_tag_id) && empty($this->sire_id)) {
                $sire = static::getOneRow(['id', 'tag_id'], ['tag_id' => $this->sire_tag_id]);
                if (!empty($sire)) {
                    $this->sire_id = $sire['id'];
                }
            }
            if (!empty($this->dam_tag_id) && empty($this->dam_id)) {
                $dam = static::getOneRow(['id', 'tag_id'], ['tag_id' => $this->dam_tag_id]);
                if (!empty($dam)) {
                    $this->dam_id = $dam['id'];
                }
            }
            //set sex
            switch ($this->animal_type) {
                case self::ANIMAL_TYPE_HEIFER:
                case self::ANIMAL_TYPE_COW:
                case self::ANIMAL_TYPE_FEMALE_CALF:
                    $this->sex = 2;
                    break;
                case self::ANIMAL_TYPE_MALE_CALF:
                case self::ANIMAL_TYPE_BULL:
                case self::ANIMAL_TYPE_AI_STRAW:
                    $this->sex = 1;
                    break;
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
        return TableAttribute::TABLE_ANIMAL;
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
            'reg_date',
            'derivedBirthdate',
            'birthdate',
            'animal_approxage',
            'deformities',
            'sire_type',
            'animal_sireknown',
            'sire_tag_id',
            'animal_damknown',
            'dam_tag_id',
            'main_breed',
            'breed_composition',
            'second_breed',
            'entry_type',
            'entry_date',
            'purchase_cost',
            'animal_photo',
            'latitude',
            'longitude',
            'altitude',
            'gprs_accuracy',
            'animal_type',
            'hair_sample_id',
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

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return [
            'sire_id',
            'animal_sireknown',
            'farm_id',
            'herd_id',
            'animal_damknown',
            'sire_tag_id',
            'sire_type',
            'dam_id',
            'dam_tag_id'
        ];
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderFieldsMapping(): array
    {
        return [
            'animal_type' => [
                'type' => TableAttribute::INPUT_TYPE_SELECT,
                'choices' => function ($field) {
                    return Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, false, null, [], []);
                },
                'tooltip' => function ($field) {
                    return static::buildChoicesTooltip(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, []);
                },
            ],
            'sire_type' => [
                'type' => TableAttribute::INPUT_TYPE_SELECT,
                'choices' => function ($field) {
                    return Choices::getList(ChoiceTypes::CHOICE_TYPE_SIRE_TYPE, false, null, [], []);
                },
                'tooltip' => function ($field) {
                    return static::buildChoicesTooltip(ChoiceTypes::CHOICE_TYPE_SIRE_TYPE, []);
                },
            ],
            'breed_composition' => [
                'type' => TableAttribute::INPUT_TYPE_SELECT,
                'choices' => function ($field) {
                    return Choices::getList(ChoiceTypes::CHOICE_TYPE_BREED_COMPOSITION, false, null, [], []);
                },
                'tooltip' => function ($field) {
                    return static::buildChoicesTooltip(ChoiceTypes::CHOICE_TYPE_BREED_COMPOSITION, []);
                }
            ],
            'main_breed' => [
                'type' => TableAttribute::INPUT_TYPE_SELECT,
                'choices' => function ($field) {
                    return Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, false, null, [], ['orderBy' => ['label' => SORT_ASC]]);
                },
                'tooltip' => function ($field) {
                    return static::buildChoicesTooltip(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, []);
                }
            ],
            'secondary_breed' => [
                'type' => TableAttribute::INPUT_TYPE_SELECT,
                'choices' => function ($field) {
                    return Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, false, null, [], ['orderBy' => ['label' => SORT_ASC]]);
                },
                'tooltip' => function ($field) {
                    return static::buildChoicesTooltip(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, []);
                }
            ],
            'reg_date' => [
                'type' => TableAttribute::INPUT_TYPE_DATE,
            ],
            'birthdate' => [
                'type' => TableAttribute::INPUT_TYPE_DATE,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderRelations()
    {
        return array_merge(['farm', 'herd', 'sire', 'dam'], $this->reportBuilderCommonRelations(), $this->reportBuilderCoreDataRelations());
    }
}
