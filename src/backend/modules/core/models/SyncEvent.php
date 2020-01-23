<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-01-13
 * Time: 11:25 PM
 */

namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use common\helpers\ArrayHelper;
use common\helpers\DbUtils;

/**
 * Class SyncEvent
 * @package backend\modules\core\models
 *
 * @property string $animalbreeding_synchormonetype
 * @property string $animalbreeding_syncnumber
 * @property string $animalbreeding_syncsemensource
 * @property string $animalbreeding_syncwhodid
 * @property string $breeding_synccost
 * @property string $breeding_synchormonetypeoth
 * @property string $breeding_syncparity
 * @property string $breeding_syncsemensourceoth
 * @property string $breeding_synctime
 * @property string $breeding_syncwhodidoth
 * @property string $breeding_syncwhodidothphone
 *
 */
class SyncEvent extends AnimalEvent implements ImportActiveRecordInterface
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
            'event_date' => 'Sync Date',
        ]);
    }

    public function reportBuilderFields(){
        $this->ignoreAdditionalAttributes = true;
        $attributes = $this->attributes();
        $attrs = [];
        $fields = TableAttribute::getData(['attribute_key'], ['table_id' => self::getDefinedTableId(), 'event_type' => self::EVENT_TYPE_SYNCHRONIZATION]);

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
            'field_agent_id',
            'animalTagId',
            'animalbreeding_syncnumber',
            'breeding_syncparity',
            'event_date',
            'breeding_synctime',
            'animalbreeding_synchormonetype',
            'animalbreeding_syncsemensource',
            'breeding_syncsemensourceoth',
            'breeding_synccost',
            'animalbreeding_syncwhodid',
            'breeding_syncwhodidoth',
            'breeding_syncwhodidothphone',
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
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_SYNCHRONIZATION, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }
}