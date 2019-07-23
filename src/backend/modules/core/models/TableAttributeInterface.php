<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-05
 * Time: 7:33 PM
 */

namespace backend\modules\core\models;


interface TableAttributeInterface
{
    /**
     * @return int
     */
    public static function getDefinedTableId(): int;

    /**
     * @return int
     */
    public static function getDefinedType(): int;

    /**
     * @return array
     */
    public function getAdditionalAttributes(): array;

    /**
     * @param string $attribute
     * @param string $attributeValueModelClass
     * @param string $foreignKeyAttribute
     * @return bool
     */
    public function saveAdditionalAttributeValue(string $attribute, string $attributeValueModelClass, string $foreignKeyAttribute);


    /**
     * @param string $attribute
     * @return bool
     */
    public function isAdditionalAttribute(string $attribute): bool;

    /**
     * @param string $attributeValueModelClass
     * @param string $foreignKeyAttribute
     * @return mixed
     */
    public function loadAdditionalAttributeValues(string $attributeValueModelClass, string $foreignKeyAttribute);


}