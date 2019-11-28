<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-05
 * Time: 7:32 PM
 */

namespace backend\modules\core\models;


use common\helpers\Str;
use common\models\ActiveRecord;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;

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

    protected function setAdditionalAttributes()
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
        //all the relations
        //country
        if (isset($this->org)) {
            $fields['org'] = function () {
                return $this->org;
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


        return $fields;
    }

    /**
     * @param ActiveRecord[] $valueModels
     * @return mixed
     */
    public function loadAdditionalAttributeValues($valueModels)
    {
        $additionalAttributes = array_flip($this->getAdditionalAttributeIds());
        foreach ($valueModels as $model) {
            $attribute = $additionalAttributes[$model->attribute_id] ?? null;
            $isMultiSelectField = $this->isMultiSelectAttribute($attribute);
            $valueAttribute = $isMultiSelectField ? 'attribute_value_json' : 'attribute_value';
            $this->{$attribute} = $model->{$valueAttribute};
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

    /**
     * @param string $attributeValueModelClass
     * @param string $foreignKeyAttribute
     * @param bool $insert
     * @throws \Exception
     */
    protected function saveAdditionalAttributes(string $attributeValueModelClass, string $foreignKeyAttribute, $insert = true)
    {
        $this->ignoreAdditionalAttributes = false;

        $attributes = [];
        foreach ($this->getAttributes() as $attribute => $val) {
            if ($this->isAdditionalAttribute($attribute)) {
                $columns = $this->saveAdditionalAttributeValue($attribute, $attributeValueModelClass, $foreignKeyAttribute, $insert);
                if (is_array($columns)) {
                    $attributes[] = $columns;
                }
            }
        }
        if (!empty($attributes)) {
            $attributeValueModelClass::insertMultiple($attributes);
        }

    }

    /**
     * @param string $attribute
     * @param string $attributeValueModelClass
     * @param string $foreignKeyAttribute
     * @param bool $insert
     * @return bool|array
     * @throws \Exception
     */
    public function saveAdditionalAttributeValue(string $attribute, string $attributeValueModelClass, string $foreignKeyAttribute, $insert = true)
    {
        if (null === $this->{$attribute}) {
            return false;
        }
        /* @var $attributeValueModelClass ActiveRecord */
        $additionalAttributeIds = $this->getAdditionalAttributeIds();
        $attributeId = $additionalAttributeIds[$attribute];
        $model = new $attributeValueModelClass([$foreignKeyAttribute => $this->id, 'attribute_id' => $attributeId]);
        $isMultiSelectField = $this->isMultiSelectAttribute($attribute);
        $valueAttribute = 'attribute_value';
        if ($isMultiSelectField) {
            $valueAttribute = 'attribute_value_json';
        }
        $attributeValue = $this->{$attribute};
        if ($isMultiSelectField) {
            if (!is_array($attributeValue)) {
                $attributeValue = array_map('trim', explode(' ', $attributeValue));
            }
            $attributeValue = array_unique($attributeValue);
        }
        if ($insert) {
            if (Str::isEmpty($attributeValue)) {
                return false;
            }
            return [
                $foreignKeyAttribute => $this->id,
                'attribute_value' => !$isMultiSelectField ? $attributeValue : null,
                'attribute_value_json' => $isMultiSelectField ? json_encode($attributeValue) : null,
                'attribute_id' => $attributeId,
            ];
        }

        $newModel = $attributeValueModelClass::find()->andWhere([$foreignKeyAttribute => $this->id, 'attribute_id' => $attributeId])->one();
        if (null === $newModel) {
            $newModel = clone $model;
        }
        $newModel->{$valueAttribute} = $attributeValue;
        return $newModel->save(false);
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