<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-28
 * Time: 2:24 PM
 */

namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use common\helpers\ArrayHelper;
use common\helpers\DbUtils;

/**
 * Class ExitsEvent
 * @package backend\modules\core\models
 *
 *
 */
class ExitsEvent extends AnimalEvent
{
    public function reportBuilderFields(){
        $this->ignoreAdditionalAttributes = true;
        $attributes = $this->attributes();
        $attrs = [];
        $fields = TableAttribute::getData(['attribute_key'], ['table_id' => self::getDefinedTableId(), 'event_type' => self::EVENT_TYPE_EXITS]);

        foreach ($fields as $k => $field){
            $attrs[] = $field['attribute_key'];
        }
        $attrs = array_merge($attributes, $attrs);
        $unwanted = array_merge($this->reportBuilderUnwantedFields(), ['lactation_id', 'lactation_number']);
        $attrs = array_diff($attrs, $unwanted);
        sort($attrs);
        return $attrs;
    }

}