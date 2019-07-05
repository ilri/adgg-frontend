<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-05
 * Time: 7:32 PM
 */

namespace backend\modules\core\models;


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
}