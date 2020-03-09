<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-05
 * Time: 7:33 PM
 */

namespace backend\modules\core\models;


/**
 * Interface TableAttributeInterface
 * @package backend\modules\core\models
 *
 * @property bool $ignoreAdditionalAttributes
 */
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
     * @return bool
     */
    public function isAdditionalAttribute(string $attribute): bool;

    /**
     * @return mixed
     */
    public function loadAdditionalAttributeValues();

    /**
     * @return array
     */
    public function getOriginalAttributesListData();

    /**
     * @return array
     */
    public function reportBuilderAdditionalUnwantedFields(): array;


}