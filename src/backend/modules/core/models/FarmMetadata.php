<?php

namespace backend\modules\core\models;

use common\helpers\ArrayHelper;
use common\helpers\Utils;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use yii\base\InvalidArgumentException;

/**
 * This is the model class for table "core_farm_metadata".
 *
 * @property int $id
 * @property int $farm_id
 * @property int $type
 * @property string|array|null $additional_attributes
 * @property string $created_at
 * @property int|null $created_by
 *
 * @property Farm $farm
 */
abstract class FarmMetadata extends ActiveRecord implements ActiveSearchInterface, TableAttributeInterface, FarmMetadataInterface, UploadExcelInterface
{
    use ActiveSearchTrait, TableAttributeTrait;

    //types
    const TYPE_FEEDING_SYSTEMS_METADATA = 1;//feeding surveys
    const TYPE_HEALTH_SERVICES_METADATA = 2;//health surveys
    const TYPE_BREEDING_TECHNOLOGIES_METADATA = 3;//breeding technologies


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
            [['farm_id', 'type'], 'integer'],
            [['additional_attributes'], 'safe'],
            ['type', 'unique', 'targetAttribute' => ['farm_id', 'type'], 'message' => '{attribute} {value} already exists.'],
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
        ];
    }

    public static function getDefinedTableId(): int
    {
        return ExtendableTable::TABLE_FARM_METADATA;
    }

    /**
     * @param int $intVal
     * @return string
     */
    public static function decodeType($intVal): string
    {
        switch ($intVal) {
            case self::TYPE_FEEDING_SYSTEMS_METADATA:
                return 'Cattle Feeding Systems';
            case self::TYPE_HEALTH_SERVICES_METADATA:
                return 'Cattle Health Services';
            case self::TYPE_BREEDING_TECHNOLOGIES_METADATA:
                return 'Cattle Breeding Technologies';
            default:
                throw new InvalidArgumentException();
        }
    }

    /**
     * @param bool $prompt
     * @return array
     */
    public static function typeOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::TYPE_FEEDING_SYSTEMS_METADATA => static::decodeType(self::TYPE_FEEDING_SYSTEMS_METADATA),
            self::TYPE_HEALTH_SERVICES_METADATA => static::decodeType(self::TYPE_HEALTH_SERVICES_METADATA),
            self::TYPE_BREEDING_TECHNOLOGIES_METADATA => static::decodeType(self::TYPE_BREEDING_TECHNOLOGIES_METADATA),
        ], $prompt);
    }

    public function getExcelColumns()
    {
        $additionalAttributes = TableAttribute::getColumnData('attribute_key', ['table_id' => static::getDefinedTableId(), 'farm_metadata_type' => static::getDefineMetadataType()], [], ['orderBy' => ['group_id' => SORT_ASC, 'id' => SORT_ASC]]);
        return ArrayHelper::merge(['farmCode',], $additionalAttributes);
    }

    /**
     * @param int $type
     * @return string
     */
    public static function getMetadataModelClassNameByType($type)
    {
        switch ($type) {
            case self::TYPE_FEEDING_SYSTEMS_METADATA:
                return FarmMetadataFeeding::class;
            case self::TYPE_HEALTH_SERVICES_METADATA:
                return FarmMetadataHealth::class;
            case self::TYPE_BREEDING_TECHNOLOGIES_METADATA:
                return FarmMetadataBreeding::class;
            default:
                throw new InvalidArgumentException();
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
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
     * @return array
     * @throws \Exception
     */
    public function getDetailViewAttributes($metadataType, $groupId)
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
                $viewAttribute = [
                    'attribute' => $attribute,
                    'value' => $value,
                ];
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
}
