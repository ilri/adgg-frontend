<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-05-13
 * Time: 2:14 PM
 */

namespace backend\modules\core\models;


class FarmMetadataModel extends FarmMetadata
{
    public static function getDefineMetadataType(): int
    {
        return 0;
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
        $fields = TableAttribute::getData(['attribute_key'], ['table_id' => self::getDefinedTableId(), 'farm_metadata_type' => get_called_class()::getDefineMetadataType()]);

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