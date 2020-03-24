<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-11-06
 * Time: 6:50 PM
 */

namespace common\models;


use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use common\helpers\DbUtils;
use Yii;

trait ReportsTrait
{
    /**
     * @param integer $durationType
     * @param bool|string $sum
     * @param array $filters array key=>$value pair where key is the attribute name and value is the attribute value
     * @param string $dateField
     * @param null|string $from
     * @param null|string $to
     * @param mixed $condition
     * @param array $params
     * @return int
     * @throws \Exception
     */
    public static function getDashboardStats($durationType, $sum = false, $filters = [], $dateField = 'created_at', $from = null, $to = null, $condition = '', $params = [])
    {
        if (!empty($filters)) {
            foreach ($filters as $k => $v) {
                if (!(empty($v) && strlen($v) == 0)) {
                    list($condition, $params) = DbUtils::appendCondition($k, $v, $condition, $params);
                }
            }
        }
        return static::getStats($durationType, $condition, $params, $sum, $dateField, $from, $to);
    }

    /**
     * Defines fiels to be displayed in the report builder and their types for each model
     *
     *  e.g
     *  'farm_type' => [
     *       'type' => 'text', ** this can be text, number, dropdown, date
     *       'tooltip' => 'html to be displayed when hovering the field. can contain title, '
     *   ]
     *
     * @return array
     */
    public function reportBuilderFieldsMapping(): array{
        return [];
    }

    /**
     * @return array
     */
    public function reportBuilderCoreDataRelations(){
        // relations common ONLY to core data models - Animal, Events, Farm
        return ['country', 'region', 'district', 'ward', 'village', 'org', 'client'];
    }

    /**
     * @return array
     */
    public function reportBuilderCommonRelations(){
        // relations common to all models
        return [];
    }

    /**
     * @return array
     */
    public function reportBuilderRelations(){
        return $this->reportBuilderCommonRelations();
    }

    /**
     * @param string $field
     * @return string
     * TODO: define some generic text tooltips in $this->attributeHints() method of each model
     * and fetch with $this->getAttributeHint($attribute) method
     */
    public function getFieldTooltipContent(string $field){
        if(array_key_exists($field, $this->reportBuilderFieldsMapping())){
            $field_map = $this->reportBuilderFieldsMapping()[$field];
            $tooltip = $field_map['tooltip'];
            if(is_callable($tooltip)){
                return call_user_func($tooltip, $field);
            }
            return $tooltip;
        }
        return $this->getAttributeLabel($field);
    }

    public static function buildChoicesTooltip($choiceType = null, $choices = []){
        if($choiceType === null && empty($choices)){
            return '';
        }
        if($choiceType !== null && empty($choices)){
            $choices = Choices::getList($choiceType, false, null, [], []);
        }
        if (!empty($choices)){
            $content = "<div class='field-tooltip-content'>";
            $content .= "<p><b>Value</b> : <b>Label</b>";
            foreach ($choices as $value => $label){
                $content .= "<p>".$value." : ".$label."</p>";
            }
            $content .= "<p><i>Pass the value in the filter field</i></p>";
            $content .= "</div>";
            return $content;
        }
        return '';
    }

    public function reportBuilderUnwantedFields()
    {
        return [
            'country_id', 'test', 'additional_attributes', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_active', 'is_deleted', 'deleted_at', 'deleted_by',
            'password', 'password_hash', 'password_reset_token', 'profile_image', 'account_activation_token', 'auth_key', 'auto_generate_password',
            'is_main_account', 'last_login', 'level_id', 'require_password_change', 'role_id', 'timezone',
            'event_type', 'client_id', 'district_id', 'region_id', 'ward_id','village_id', 'org_id', 'animal_id'
        ];
    }

    public function reportBuilderFields()
    {
        $unwanted = array_merge($this->reportBuilderUnwantedFields(), $this->reportBuilderAdditionalUnwantedFields());
        $attributes = $this->attributes();
        $attrs = array_diff($attributes, $unwanted);
        sort($attrs);
        return $attrs;
    }

    /**
     * @return array
     * This can be used to add Model Specific unwanted fields
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return [];
    }
}