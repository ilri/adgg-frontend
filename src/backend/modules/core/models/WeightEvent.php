<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-01-14
 * Time: 1:52 AM
 */

namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use common\helpers\DbUtils;
use yii\helpers\ArrayHelper;

/**
 * Class WeightEvent
 * @package backend\modules\core\models
 *
 * @property string $body_length
 * @property string $body_score
 * @property string $heartgirth
 * @property string $weight_kg
 */
class WeightEvent extends AnimalEvent implements ImportActiveRecordInterface
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [$this->getExcelColumns(), 'safe', 'on' => self::SCENARIO_UPLOAD],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'event_date' => 'Weight Date',
        ]);
    }

    public function reportBuilderFields(){
        $this->ignoreAdditionalAttributes = true;
        $attributes = $this->attributes();
        $attrs = [];
        $fields = TableAttribute::getData(['attribute_key'], ['table_id' => self::getDefinedTableId(), 'event_type' => self::EVENT_TYPE_WEIGHTS]);

        foreach ($fields as $k => $field){
            $attrs[] = $field['attribute_key'];
        }
        $attrs = array_merge($attributes, $attrs);
        $unwanted = array_merge($this->reportBuilderUnwantedFields(), ['lactation_id', 'lactation_number']);
        $attrs = array_diff($attrs, $unwanted);
        sort($attrs);
        return $attrs;
    }


    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'animalTagId',
            'event_date',
            'weight_kg',
            'heartgirth',
            'body_length',
            'body_score',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        }
        return false;
    }

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
    public static function getDashboardStats($durationType, $sum = false, $filters = [], $dateField = 'event_date', $from = null, $to = null, $condition = '', $params = [])
    {
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_WEIGHTS, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }
}