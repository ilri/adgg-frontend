<?php

namespace backend\modules\core\models;

use common\helpers\DateUtils;
use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use yii\db\Expression;

/**
 * This is the model class for table "core_animal".
 *
 * @property int $id
 * @property string $mistro_code
 * @property string $name
 * @property int $farm_id
 * @property int $org_id
 * @property int $region_id
 * @property int $district_id
 * @property int $ward_id
 * @property int $village_id
 * @property int $is_active
 * @property string $latitude
 * @property string $longitude
 * @property string $map_address
 * @property string $latlng
 * @property string $uuid
 * @property string $tag_id
 * @property string $color
 * @property string $animal_type
 * @property string $birthdate
 * @property string $age_estimate
 * @property int $body_condition_score
 * @property array $deformities
 * @property string $udder_support
 * @property int $udder_attachment
 * @property int $udder_teat_placement
 * @property int $sire_type
 * @property int $sire_registered
 * @property int $sire_id
 * @property int $dam_registered
 * @property int $dam_id
 * @property int $main_breed
 * @property int $breed_composition
 * @property int $secondary_breed
 * @property int $is_genotyped
 * @property int $genotype_id
 * @property int $result_genotype
 * @property string $first_calv_date
 * @property int $first_calv_age
 * @property string $first_calv_date_estimate
 * @property int $first_calv_method
 * @property int $first_calv_type
 * @property string $latest_calv_date
 * @property string $latest_calv_date_estimate
 * @property int $latest_calv_type
 * @property int $parity_number
 * @property string $average_daily_milk
 * @property string $peak_milk
 * @property int $is_still_lactating
 * @property string $dry_date
 * @property int $is_pregnant
 * @property string $entry_date
 * @property int $entry_type
 * @property string $purchase_cost
 * @property string $ear_tag_photo
 * @property string $animal_photo
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property int $is_deleted
 * @property string $deleted_at
 * @property int $deleted_by
 *
 * @property Farm $farm
 */
class Animal extends ActiveRecord implements ActiveSearchInterface, TableAttributeInterface
{
    use ActiveSearchTrait, OrganizationUnitDataTrait, TableAttributeTrait;

    /**
     * @var string
     */
    public $tmp_ear_tag_photo;
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
            [['name', 'farm_id', 'org_id'], 'required'],
            ['tag_id', 'unique', 'targetAttribute' => ['org_id', 'tag_id'], 'message' => '{attribute} already exists.'],
            ['tag_id', 'number'],
            ['tag_id', 'string', 'max' => 16],
            ['mistro_code', 'unique', 'targetAttribute' => ['org_id', 'tag_id'], 'message' => '{attribute} already exists.'],
            [
                [
                    'farm_id', 'org_id', 'region_id', 'district_id', 'ward_id', 'village_id', 'is_active', 'udder_attachment', 'udder_teat_placement', 'sire_type', 'sire_registered',
                    'sire_id', 'dam_registered', 'dam_id', 'main_breed', 'breed_composition', 'secondary_breed', 'is_genotyped', 'genotype_id', 'result_genotype', 'first_calv_method',
                    'first_calv_type', 'latest_calv_type', 'is_still_lactating', 'is_pregnant', 'entry_type',
                ],
                'integer'
            ],
            ['body_condition_score', 'integer', 'min' => 1, 'max' => 5],
            ['parity_number', 'integer', 'min' => 1, 'max' => 12],
            [['latitude', 'longitude', 'purchase_cost'], 'number'],
            [['average_daily_milk', 'peak_milk'], 'number', 'min' => 0.5, 'max' => 35],
            [['birthdate', 'first_calv_date', 'first_calv_date_estimate', 'latest_calv_date', 'latest_calv_date_estimate', 'dry_date', 'entry_date'], 'date', 'format' => 'Y-m-d'],
            ['birthdate', 'validateBirthDate'],
            ['first_calv_date', 'validateFirstCalvDate'],
            ['first_calv_date_estimate', 'validateFirstCalvDateEstimate'],
            ['latest_calv_date', 'validateLatestCalvDate'],
            ['latest_calv_date_estimate', 'validateLatestCalvDateEstimate'],
            ['dry_date', 'validateDryDate'],
            ['entry_date', 'validateEntryDate'],
            ['first_calv_age', 'number', 'min' => 1.5, 'max' => 5],
            [['deformities'], 'string'],
            [['mistro_code', 'name', 'age_estimate', 'udder_support'], 'string', 'max' => 128],
            [['map_address', 'uuid', 'tmp_ear_tag_photo', 'tmp_animal_photo'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 30],
            [['animal_type'], 'string', 'max' => 20],
            [['farm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Farm::class, 'targetAttribute' => ['farm_id' => 'id']],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    public function validateBirthDate()
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->birthdate) && strtotime($this->birthdate) > strtotime(DateUtils::getToday())) {
            $this->addError('birthdate', Lang::t("{birthdate_label} cannot  be after today's date", [
                'birthdate_label' => $this->getAttributeLabel('birthdate'),
            ]));
        }
    }

    public function validateFirstCalvDate()
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->first_calv_date) && strtotime($this->first_calv_date) > strtotime(DateUtils::getToday())) {
            $this->addError('first_calv_date', Lang::t("{first_calv_date_label} cannot  be after today's date", [
                'first_calv_date_label' => $this->getAttributeLabel('first_calv_date'),
            ]));
        }
        if (!empty($this->first_calv_date) && !empty($this->birthdate)) {
            $dateDiff = DateUtils::getDateDiff($this->birthdate, $this->first_calv_date);
            if ($dateDiff->days <= 660) {
                $this->addError('first_calv_date', Lang::t("{first_calv_date_label} must be > 660 days after {birthdate_label}", [
                    'first_calv_date_label' => $this->getAttributeLabel('first_calv_date'),
                    'birthdate_label' => $this->getAttributeLabel('birthdate'),
                ]));
            }
        }
    }

    public function validateFirstCalvDateEstimate()
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->first_calv_date_estimate) && strtotime($this->first_calv_date_estimate) > strtotime(DateUtils::getToday())) {
            $this->addError('first_calv_date_estimate', Lang::t("{first_calv_date_estimate_label} cannot  be after today's date", [
                'first_calv_date_estimate_label' => $this->getAttributeLabel('first_calv_date_estimate'),
            ]));
        }
        if (!empty($this->first_calv_date_estimate) && !empty($this->birthdate)) {
            $dateDiff = DateUtils::getDateDiff($this->birthdate, $this->first_calv_date_estimate);
            if ($dateDiff->days <= 660) {
                $this->addError('first_calv_date_estimate', Lang::t("{first_calv_date_estimate_label} must be > 660 days after {birthdate_label}", [
                    'first_calv_date_estimate_label' => $this->getAttributeLabel('first_calv_date_estimate'),
                    'birthdate_label' => $this->getAttributeLabel('birthdate'),
                ]));
            }
        }
    }

    public function validateLatestCalvDate()
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->latest_calv_date) && strtotime($this->latest_calv_date) > strtotime(DateUtils::getToday())) {
            $this->addError('latest_calv_date', Lang::t("{latest_calv_date_label} cannot  be after today's date", [
                'latest_calv_date_label' => $this->getAttributeLabel('latest_calv_date'),
            ]));
        }
        if (!empty($this->latest_calv_date) && !empty($this->birthdate)) {
            $dateDiff = DateUtils::getDateDiff($this->birthdate, $this->latest_calv_date);
            if ($dateDiff->days <= 660) {
                $this->addError('latest_calv_date', Lang::t("{latest_calv_date_label} must be > 660 days after {birthdate_label}", [
                    'latest_calv_date_label' => $this->getAttributeLabel('latest_calv_date'),
                    'birthdate_label' => $this->getAttributeLabel('birthdate'),
                ]));
            }
        }
    }

    public function validateLatestCalvDateEstimate()
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->latest_calv_date_estimate) && strtotime($this->latest_calv_date_estimate) > strtotime(DateUtils::getToday())) {
            $this->addError('latest_calv_date_estimate', Lang::t("{latest_calv_date_estimate_label} cannot  be after today's date", [
                'latest_calv_date_estimate_label' => $this->getAttributeLabel('latest_calv_date_estimate'),
            ]));
        }
        if (!empty($this->latest_calv_date_estimate) && !empty($this->birthdate)) {
            $dateDiff = DateUtils::getDateDiff($this->birthdate, $this->latest_calv_date_estimate);
            if ($dateDiff->days <= 660) {
                $this->addError('latest_calv_date_estimate', Lang::t("{latest_calv_date_estimate_label} must be > 660 days after {birthdate_label}", [
                    'latest_calv_date_estimate_label' => $this->getAttributeLabel('latest_calv_date_estimate'),
                    'birthdate_label' => $this->getAttributeLabel('birthdate'),
                ]));
            }
        }
    }

    public function validateDryDate()
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->dry_date) && strtotime($this->dry_date) > strtotime(DateUtils::getToday())) {
            $this->addError('dry_date', Lang::t("{dry_date_label} cannot  be after today's date", [
                'dry_date_label' => $this->getAttributeLabel('dry_date'),
            ]));
        }
    }

    public function validateEntryDate()
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!empty($this->entry_date) && strtotime($this->entry_date) > strtotime(DateUtils::getToday())) {
            $this->addError('entry_date', Lang::t("{entry_date_label} cannot  be after today's date", [
                'entry_date_label' => $this->getAttributeLabel('entry_date'),
            ]));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mistro_code' => 'Mistro Code',
            'name' => 'Name',
            'farm_id' => 'Farm',
            'org_id' => 'Country',
            'region_id' => 'Region',
            'district_id' => 'District',
            'ward_id' => 'Ward',
            'village_id' => 'Village',
            'is_active' => 'Active',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'map_address' => 'Map Address',
            'uuid' => 'Uuid',
            'tag_id' => 'Ear Tag ID',
            'color' => 'Color',
            'animal_type' => 'Animal Type',
            'birthdate' => 'Birth Date',
            'age_estimate' => 'Age Estimate',
            'body_condition_score' => 'Body Condition Score',
            'deformities' => 'Deformities',
            'udder_support' => 'Udder Support',
            'udder_attachment' => 'Udder Attachment',
            'udder_teat_placement' => 'Udder Teat Placement',
            'sire_type' => 'Sire Type',
            'sire_registered' => 'Sire Registered',
            'sire_id' => 'Sire ID',
            'dam_registered' => 'Dam Registered',
            'dam_id' => 'Dam ID',
            'main_breed' => 'Main Breed',
            'breed_composition' => 'Breed Composition',
            'secondary_breed' => 'Secondary Breed',
            'is_genotyped' => 'Is Genotyped',
            'genotype_id' => 'Genotype ID',
            'result_genotype' => 'Result Genotype',
            'first_calv_date' => 'First Calv Date',
            'first_calv_age' => 'First Calv Age',
            'first_calv_date_estimate' => 'First Calv Date Estimate',
            'first_calv_method' => 'First Calv Method',
            'first_calv_type' => 'First Calv Type',
            'latest_calv_date' => 'Latest Calv Date',
            'latest_calv_date_estimate' => 'Latest Calv Date Estimate',
            'latest_calv_type' => 'Latest Calv Type',
            'parity_number' => 'Parity Number',
            'average_daily_milk' => 'Average Daily Milk',
            'peak_milk' => 'Peak Milk',
            'is_still_lactating' => 'Is Still Lactating',
            'dry_date' => 'Dry Date',
            'is_pregnant' => 'Is Pregnant',
            'entry_date' => 'Entry Date',
            'entry_type' => 'Entry Type',
            'purchase_cost' => 'Purchase Cost',
            'ear_tag_photo' => 'Ear Tag Photo',
            'animal_photo' => 'Animal Photo',
            'tmp_ear_tag_photo' => 'Ear Tag Photo',
            'tmp_animal_photo' => 'Animal Photo',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFarm()
    {
        return $this->hasOne(Farm::class, ['id' => 'farm_id']);
    }

    /**
     *  {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['mistro_code', 'mistro_code'],
            ['tag_id', 'tag_id'],
            ['name', 'name'],
            ['color', 'color'],
            'farm_id',
            'org_id',
            'region_id',
            'district_id',
            'ward_id',
            'village_id',
            'is_active',
            'animal_type',
            'body_condition_score',
            'dam_id',
            'is_pregnant',
            'sire_id',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ignoreAdditionalAttributes = true;
            if (!empty($this->latitude) && !empty($this->longitude)) {
                $this->latlng = new Expression("ST_GeomFromText('POINT({$this->latitude} {$this->longitude})')");
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->ignoreAdditionalAttributes = false;

        foreach ($this->getAttributes() as $attribute => $val) {
            if ($this->isAdditionalAttribute($attribute)) {
                $this->saveAdditionalAttributeValue($attribute);
            }
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAdditionalAttributeValues(AnimalAttributeValue::class, 'animal_id');
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
     * @param string $attribute
     * @return bool
     * @throws \Exception
     */
    public function saveAdditionalAttributeValue(string $attribute): bool
    {
        if (null === $this->{$attribute}) {
            return false;
        }
        $attributeId = TableAttribute::getAttributeId(static::getDefinedTableId(), $attribute);
        $model = AnimalAttributeValue::find()->andWhere(['animal_id' => $this->id, 'attribute_id' => $attributeId])->one();
        if (null === $model) {
            $model = new AnimalAttributeValue(['animal_id' => $this->id, 'attribute_id' => $attributeId]);
        }
        $model->attribute_value = $this->{$attribute};
        return $model->save(false);
    }
}
