<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-05
 * Time: 7:32 PM
 */

namespace backend\modules\core\models;


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
        $additionalAttributes = $this->getAdditionalAttributes();

        return array_merge($attributes, $additionalAttributes);
    }

    public function getAdditionalAttributes(): array
    {
        if (empty($this->_additionalAttributes)) {
            $this->setAdditionalAttributes();
        }

        return $this->_additionalAttributes;
    }

    protected function setAdditionalAttributes()
    {
        $tableId = static::getDefinedTableId();
        $type = static::getDefinedType();
        $attributes = TableAttribute::getDefinedAttributes($tableId, $type);
        if (!empty($attributes)) {
            $this->_additionalAttributes = $attributes;
        } else {
            $this->_additionalAttributes = [];
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
     * @param string $attributeValueModelClass
     * @param string $foreignKeyAttribute
     * @return mixed
     * @throws \Exception
     */
    public function loadAdditionalAttributeValues(string $attributeValueModelClass, string $foreignKeyAttribute)
    {
        foreach ($this->getAdditionalAttributes() as $attribute) {
            $attributeId = TableAttribute::getAttributeId(static::getDefinedTableId(), $attribute);
            $value = $attributeValueModelClass::getScalar('attribute_value', [$foreignKeyAttribute => $this->id, 'attribute_id' => $attributeId]);
            $this->{$attribute} = $value !== false ? $value : null;
        }
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
                    'data' => LookupList::getList($attributeModel->list_type_id),
                    'options' => [
                        'placeholder' => '[select one]',
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
}