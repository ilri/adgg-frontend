<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-05
 * Time: 7:32 PM
 */

namespace backend\modules\core\models;


use common\models\ActiveRecord;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;

/**
 * Trait TableAttributeTrait
 * @package backend\modules\core\models
 *
 * @property string|array $additional_attributes
 */
trait TableAttributeTrait
{
    /**
     * @var array
     */
    private $_additionalAttributes;

    /**
     * @var array
     */
    private $_additionalAttributeIds;

    /**
     * @var array
     */
    private $_additionalAttributesInputTypes;

    /**
     * @var array
     */
    private $_additionalAttributesListTypeIds;

    /**
     * @var array
     */
    public $ignoreAdditionalAttributes = false;

    /**
     * Returns the list of all attribute names of the model.
     * The default implementation will return all column names of the table associated with this AR class.
     * @return array list of attribute names.
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        if ($this->ignoreAdditionalAttributes) {
            return $attributes;
        }
        $additionalAttributes = (array)$this->getAdditionalAttributes();

        return array_merge($attributes, $additionalAttributes);
    }

    public function getAdditionalAttributes(): array
    {
        if (empty($this->_additionalAttributes)) {
            $this->setAdditionalAttributes();
        }

        return $this->_additionalAttributes;
    }

    public function getAdditionalAttributeIds(): array
    {
        if (empty($this->_additionalAttributeIds)) {
            $this->setAdditionalAttributes();
        }

        return $this->_additionalAttributeIds;
    }

    public function getAdditionalAttributesInputTypes(): array
    {
        if (empty($this->_additionalAttributesInputTypes)) {
            $this->setAdditionalAttributes();
        }

        return $this->_additionalAttributesInputTypes;
    }

    public function getAdditionalAttributesListTypeIds(): array
    {
        if (empty($this->_additionalAttributesListTypeIds)) {
            $this->setAdditionalAttributes();
        }

        return $this->_additionalAttributesListTypeIds;
    }

    public function setAdditionalAttributes()
    {
        $tableId = static::getDefinedTableId();
        $type = static::getDefinedType();
        $attributes = TableAttribute::getDefinedAttributes($tableId, $type);
        if (!empty($attributes)) {
            $attributeKeys = [];
            $attributeIds = [];
            $attributesInputTypes = [];
            $attributeListTypeIds = [];
            foreach ($attributes as $v) {
                $attributeKeys[] = $v['attribute_key'];
                $attributeIds[$v['attribute_key']] = $v['id'];
                $attributesInputTypes[$v['attribute_key']] = $v['input_type'];
                if (!empty($v['list_type_id'])) {
                    $attributeListTypeIds[$v['attribute_key']] = $v['list_type_id'];
                }
            }
            $this->_additionalAttributes = $attributeKeys;
            $this->_additionalAttributeIds = $attributeIds;
            $this->_additionalAttributesInputTypes = $attributesInputTypes;
            $this->_additionalAttributesListTypeIds = $attributeListTypeIds;
        } else {
            $this->_additionalAttributes = [];
            $this->_additionalAttributeIds = [];
            $this->_additionalAttributesInputTypes = [];
            $this->_additionalAttributesListTypeIds = [];
        }
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function isAdditionalAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->getAdditionalAttributes());
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function isMultiSelectAttribute(string $attribute): bool
    {
        $inputTypes = $this->getAdditionalAttributesInputTypes();
        return $inputTypes[$attribute] == TableAttribute::INPUT_TYPE_MULTI_SELECT;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function isSingleSelectAttribute(string $attribute): bool
    {
        $inputTypes = $this->getAdditionalAttributesInputTypes();
        return $inputTypes[$attribute] == TableAttribute::INPUT_TYPE_SELECT;
    }

    /**
     * @return array|false
     */
    public function apiResourceFields()
    {
        $fields = parent::fields();
        //all additional fields here
        foreach ($this->getAdditionalAttributes() as $attribute) {
            if (!isset($fields[$attribute])) {
                $fields[$attribute] = function () use ($attribute) {
                    return $this->{$attribute};
                };
            }
            if ($this->isSingleSelectAttribute($attribute) || $this->isMultiSelectAttribute($attribute)) {
                $fields['decoded_' . $attribute] = function () use ($attribute) {
                    $listTypeIds = $this->getAdditionalAttributesListTypeIds();
                    $listTypeId = $listTypeIds[$attribute] ?? null;
                    if (null === $listTypeId) {
                        return $this->{$attribute};
                    }
                    if ($this->isMultiSelectAttribute($attribute)) {
                        return Choices::getMultiSelectLabel($this->{$attribute}, $listTypeId);
                    } else {
                        return Choices::getLabel($listTypeId, $this->{$attribute});
                    }
                };
            }
        }
        //all the relations here
        //animal
        if (isset($this->animal)) {
            $fields['animal'] = function () {
                $attributes = $this->animal->attributes;
                unset($attributes['latlng'], $attributes['additional_attributes']);
                return $attributes;
            };
        }
        //farm attributes without the relations
        if (isset($this->farm)) {
            $fields['farm'] = function () {
                $attributes = $this->farm->attributes;
                unset($attributes['additional_attributes'], $attributes['latlng']);
                return $attributes;
            };
        }

        //herd attributes without the relations
        if (isset($this->herd)) {
            $fields['herd'] = function () {
                $attributes = $this->herd->attributes;
                unset($attributes['additional_attributes'], $attributes['latlng']);
                return $attributes;
            };
        }
        //country
        if (isset($this->country)) {
            $fields['country'] = function () {
                return $this->country;
            };
        }
        //organization
        if (isset($this->org)) {
            $fields['org'] = function () {
                return $this->org;
            };
        }

        //client attributes without the relations
        if (isset($this->client)) {
            $fields['client'] = function () {
                $attributes = $this->client->attributes;
                unset($attributes['additional_attributes']);
                return $attributes;
            };
        }
        //region
        if (isset($this->region)) {
            $fields['region'] = function () {
                return $this->region;
            };
        }
        //district
        if (isset($this->district)) {
            $fields['district'] = function () {
                return $this->district;
            };
        }
        //ward
        if (isset($this->ward)) {
            $fields['ward'] = function () {
                return $this->ward;
            };
        }
        //village
        if (isset($this->village)) {
            $fields['village'] = function () {
                return $this->village;
            };
        }
        //fieldAgent
        if (isset($this->fieldAgent)) {
            $fields['fieldAgent'] = function () {
                $attributes = $this->fieldAgent->attributes;
                unset($attributes['additional_attributes']);
                return $attributes;
            };
        }

        //excluded fields
        if ($this->hasAttribute('latlng')) {
            $excludedFields = ['latlng'];
            foreach ($excludedFields as $f) {
                if (isset($fields[$f])) {
                    unset($fields[$f]);
                }
            }
        }
        if ($this->hasAttribute('additional_attributes')) {
            $excludedFields = ['additional_attributes'];
            foreach ($excludedFields as $f) {
                if (isset($fields[$f])) {
                    unset($fields[$f]);
                }
            }
        }

        return $fields;
    }

    /**
     * @return mixed
     */
    public function loadAdditionalAttributeValues()
    {
        if (empty($this->additional_attributes)) {
            return false;
        }
        if (!is_array($this->additional_attributes)) {
            $this->additional_attributes = json_decode($this->additional_attributes, true);
        }
        $additionalAttributes = array_flip($this->getAdditionalAttributeIds());
        foreach ($this->additional_attributes as $attributeId => $val) {
            $attribute = $additionalAttributes[$attributeId] ?? null;
            if (!empty($attribute)) {
                $this->{$attribute} = $val;
            }
        }
    }

    /**
     * @param string $attribute
     * @param string $attributeValueModelClass
     * @param string $foreignKeyAttribute
     * @return mixed
     * @throws \Exception
     */
    public function loadAttributeValue(string $attribute, string $attributeValueModelClass, string $foreignKeyAttribute)
    {
        /* @var $attributeValueModelClass ActiveRecord */
        $additionalAttributeIds = $this->getAdditionalAttributeIds();
        $attributeId = $additionalAttributeIds[$attribute];
        $isMultiSelectField = $this->isMultiSelectAttribute($attribute);
        $valueAttribute = $isMultiSelectField ? 'attribute_value_json' : 'attribute_value';
        $value = $attributeValueModelClass::getScalar($valueAttribute, [$foreignKeyAttribute => $this->id, 'attribute_id' => $attributeId]);
        if (empty($value)) {
            $value = null;
        }
        $this->{$attribute} = $value;
    }

    protected function setAdditionalAttributesValues()
    {
        $this->ignoreAdditionalAttributes = false;

        $attributes = [];
        /* @var $attributeValueModelClass ActiveRecord */
        $additionalAttributeIds = $this->getAdditionalAttributeIds();
        foreach ($this->getAttributes() as $attribute => $val) {
            if ($this->isAdditionalAttribute($attribute)) {
                $attributeId = $additionalAttributeIds[$attribute];
                $attributeValue = $this->{$attribute};
                $isMultiSelectField = $this->isMultiSelectAttribute($attribute);
                if ($isMultiSelectField) {
                    if (!is_array($attributeValue)) {
                        $attributeValue = array_map('trim', explode(' ', $attributeValue));
                    }
                    $attributeValue = array_unique($attributeValue);
                }
                $attributes[$attributeId] = $attributeValue;
            }
        }
        $this->additional_attributes = $attributes;

        $this->ignoreAdditionalAttributes = true;
    }

    /**
     * @param ActiveForm $form
     * @param $attribute
     * @param array $options
     * @return \yii\widgets\ActiveField
     * @throws \Exception
     */
    public function renderAdditionalAttribute(ActiveForm $form, $attribute, $options = [])
    {
        $attributeModel = TableAttribute::find()->andWhere(['attribute_key' => $attribute, 'table_id' => static::getDefinedTableId()])->one();
        if ($attributeModel === null) {
            return null;
        }
        if ($attributeModel->default_value) {
            $this->{$attribute} = $attributeModel->default_value;
        }
        $fieldHtml = null;
        switch ($attributeModel->input_type) {
            case TableAttribute::INPUT_TYPE_TEXT:
                $fieldHtml = $form->field($this, $attribute)->textInput($options);
                break;
            case TableAttribute::INPUT_TYPE_NUMBER:
                $options['type'] = 'number';
                $fieldHtml = $form->field($this, $attribute)->textInput($options);
                break;
            case TableAttribute::INPUT_TYPE_EMAIL:
                $options['type'] = 'email';
                $fieldHtml = $form->field($this, $attribute)->textInput($options);
                break;
            case TableAttribute::INPUT_TYPE_CHECKBOX:
                $fieldHtml = $form->field($this, $attribute)->checkbox($options);
                break;
            case TableAttribute::INPUT_TYPE_SELECT:
                $fieldHtml = $form->field($this, $attribute)->widget(Select2::class, [
                    'data' => Choices::getList($attributeModel->list_type_id),
                    'options' => [
                        'placeholder' => '[select one]',
                    ],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                break;
            case TableAttribute::INPUT_TYPE_MULTI_SELECT:
                $fieldHtml = $form->field($this, $attribute)->widget(Select2::class, [
                    'data' => Choices::getList($attributeModel->list_type_id),
                    'options' => [
                        'placeholder' => '[select]',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                break;
            case TableAttribute::INPUT_TYPE_TEXTAREA:
                $fieldHtml = $form->field($this, $attribute)->textarea($options);
                break;
            case TableAttribute::INPUT_TYPE_DATE:
                $options['class'] = 'form-control show-datepicker';
                $fieldHtml = $form->field($this, $attribute)->textInput($options);
                break;

        }

        return $fieldHtml;
    }

    /**
     * @return array
     */
    public function getOriginalAttributesListData()
    {
        $this->ignoreAdditionalAttributes = true;
        $attributes = $this->getAttributes();
        $this->ignoreAdditionalAttributes = false;
        $formattedAttributes = [];
        foreach ($attributes as $attribute => $v) {
            $formattedAttributes[$attribute] = $this->getAttributeLabel($attribute) . ' (' . $attribute . ')';
        }
        return $formattedAttributes;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getOtherAttributeLabels()
    {
        return TableAttribute::getListData('attribute_key', 'attribute_label', false, ['table_id' => static::getDefinedTableId()]);
    }
}