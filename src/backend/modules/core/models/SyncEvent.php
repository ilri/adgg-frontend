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
class SyncEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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
            'breeding_synchormonetypeoth',
            'animalbreeding_syncsemensource',
            'breeding_syncsemensourceoth',
            'breeding_sync_semen_supplier',
            'breeding_sync_semen_supplier_other',
            'animalbreeding_syncwhodid',
            'breeding_syncwhodidoth',
            'breeding_syncwhodidothphone',
            'breeding_sync_payment_modes',
            'breeding_synccost',
            'breeding_sync_is_subsidised',
            'breeding_sync_voucher',
        ];
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

    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_SYNCHRONIZATION;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['lactation_id', 'lactation_number'];
    }
}