<?php

namespace backend\modules\core\models;

use common\helpers\ArrayHelper;
use common\helpers\DbUtils;
use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_farm_metadata".
 *
 * @property int $id
 * @property int $farm_id
 * @property int $type
 * @property string|array|null $additional_attributes
 * @property string $created_at
 * @property int|null $created_by
 * @property int $country_id
 *
 * @property Farm $farm
 * @property FarmMetadataType $metadataType
 */
class FarmMetadata extends ActiveRecord implements ActiveSearchInterface, TableAttributeInterface, UploadExcelInterface
{
    use ActiveSearchTrait, TableAttributeTrait, CountryDataTrait;

    //types
    const TYPE_FEEDING_SYSTEMS_METADATA = 1;
    const TYPE_HEALTH_SERVICES_METADATA = 2;
    const TYPE_BREEDING_TECHNOLOGIES_METADATA = 3;
    const TYPE_BREEDING_BULLS = 4;
    const TYPE_BREEDING_OTHER_BULLS = 5;
    const TYPE_BREEDING_SCHEME_BULLS = 6;
    const TYPE_BREEDING_AI_PROVIDERS = 7;
    const TYPE_HOUSEHOLD_MEMBERS = 8;
    const TYPE_TECHNOLOGY_MOBILIZATION = 9;
    const TYPE_MILK_UTILIZATION = 10;
    const TYPE_MILK_UTILIZATION_BUYER = 11;
    const TYPE_IMPROVED_FODDER_ADOPTION = 12;
    const TYPE_FEEDBACK_TO_HOUSEHOLD = 13;
    const TYPE_LAND_OWNERSHIP = 14;
    const TYPE_WATER_SOURCE = 15;
    const TYPE_LIVESTOCK_DETAILS = 16;
    const TYPE_OTHER_SPECIES_DETAILS = 17;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_farm_metadata}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['farm_id', 'type'], 'required'],
            [['farm_id', 'type', 'country_id'], 'integer'],
            [['additional_attributes'], 'safe'],
            ['type', 'uniqueTypeValidator'],
            [$this->getExcelColumns(), 'safe', 'on' => self::SCENARIO_UPLOAD],
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
            'farm_id' => 'Farm ID',
            'type' => 'Type',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'country_id' => 'Country Id',
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

    public function searchParams()
    {
        return [
            'farm_id',
            'type',
            'country_id',
        ];
    }

    public function uniqueTypeValidator()
    {
        if ($this->hasErrors()) {
            return false;
        }
        if ($this->metadataType->farmer_has_multiple) {
            return false;
        }
        $condition = '[[farm_id]]=:farm_id AND [[type]]=:type';
        $params = [':farm_id' => $this->farm_id, ':type' => $this->type];
        if (!empty($this->id)) {
            //allow updating the same record
            list($condition, $params) = DbUtils::appendCondition('id', $this->id, $condition, $params, 'AND', '<>');
        }
        if (static::exists($condition, $params)) {
            $this->addError('type', Lang::t('Type {type} already exist for farm_id = {farm_id}', [
                'type' => $this->type,
                'farm_id' => $this->farm_id
            ]));
        }
    }

    public static function getDefinedTableId(): int
    {
        return TableAttribute::TABLE_FARM_METADATA;
    }

    public function getExcelColumns()
    {
        $additionalAttributes = TableAttribute::getColumnData('attribute_key', ['table_id' => static::getDefinedTableId(), 'farm_metadata_type' => static::getDefineMetadataType()], [], ['orderBy' => ['group_id' => SORT_ASC, 'id' => SORT_ASC]]);
        return ArrayHelper::merge(['farmCode',], $additionalAttributes);
    }

    /**
     * @param int $type
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public static function getMetadataModelClassNameByType($type)
    {
        $model = FarmMetadataType::loadModel(['code' => $type]);
        return $model->model_class_name;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->country_id = $this->farm->country_id ?? null;
            $this->setAdditionalAttributesValues();

            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAdditionalAttributeValues();
    }

    /**
     * @param $metadataType
     * @param $groupId
     * @param bool $gridView
     * @return array
     * @throws \Exception
     */
    public function getViewAttributes($metadataType, $groupId, $gridView = false)
    {
        $viewAttributes = [];
        $groupAttributes = TableAttribute::getData(['attribute_key'], ['farm_metadata_type' => $metadataType, 'group_id' => $groupId]);
        foreach ($groupAttributes as $groupAttribute) {
            $attribute = $groupAttribute['attribute_key'];
            $viewAttribute = [
                'attribute' => $attribute,
            ];
            if ($this->isSingleSelectAttribute($attribute) || $this->isMultiSelectAttribute($attribute)) {
                $choiceTypeIds = $this->getAdditionalAttributesListTypeIds();
                $choiceTypeId = $choiceTypeIds[$attribute] ?? null;
                if ($this->isSingleSelectAttribute($attribute)) {
                    $value = Choices::getLabel($choiceTypeId, $this->{$attribute});
                } else {
                    $value = Choices::getMultiSelectLabel($this->{$attribute}, $choiceTypeId);
                }
                if ($gridView == true) {
                    $viewAttribute = [
                        'attribute' => $attribute,
                        'value' => function () use ($value) {
                            return $value;
                        },
                    ];
                } else {
                    $viewAttribute = [
                        'attribute' => $attribute,
                        'value' => $value,
                    ];
                }

            } elseif ($this->isDateAttribute($attribute)) {
                $viewAttribute = [
                    'attribute' => $attribute,
                    'format' => ['date', 'php:m/d/Y']
                ];
            } elseif ($this->isCheckboxAttribute($attribute)) {
                $viewAttribute = [
                    'attribute' => $attribute,
                    'format' => 'boolean',
                ];
            }
            $viewAttributes[] = $viewAttribute;
        }
        return $viewAttributes;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetadataType()
    {
        return $this->hasOne(FarmMetadataType::class, ['code' => 'type']);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function reportBuilderFields()
    {
        $this->ignoreAdditionalAttributes = true;
        $attributes = $this->attributes();
        $attrs = [];
        $fields = TableAttribute::getData(['attribute_key'], ['table_id' => self::getDefinedTableId(), 'farm_metadata_type' => static::getDefineMetadataType()]);

        foreach ($fields as $k => $field) {
            $attrs[] = $field['attribute_key'];
        }
        $attrs = array_merge($attributes, $attrs);
        $unwanted = array_merge($this->reportBuilderUnwantedFields(), $this->reportBuilderAdditionalUnwantedFields());
        $attrs = array_diff($attrs, $unwanted);
        sort($attrs);
        return $attrs;
    }
}
