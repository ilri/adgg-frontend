<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-01-13
 * Time: 1:26 PM
 */

namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use common\helpers\ArrayHelper;
use common\helpers\DbUtils;

/**
 * Class AIEvent
 * @package backend\modules\core\models
 *
 * @property string $breeding_aidate
 * @property integer $breeding_aibodycondition
 * @property integer $breeding_aitype
 * @property integer $breeding_aistrawtype
 * @property string $breeding_aistrawid
 * @property string $breeding_aisemensource
 * @property string $breeding_aisemensourceoth
 * @property string $breeding_aisirecountry
 * @property string $breeding_aisirebreed
 * @property string $breeding_aisirebreedoth
 * @property string $breeding_aibreedcomposition
 * @property string $breeding_aicost
 * @property string $breeding_aicowweight
 * @property string $breeding_aisemenbatch
 *
 */
class AIEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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
            'event_date' => 'AI Date',
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
            'event_date',
            'breeding_service_number',
            'breeding_aibodycondition',
            'breeding_aitype',
            'breeding_aisemensource',
            'breeding_aisemensourceoth',
            'breeding_ai_semen_supplier',
            'breeding_ai_semen_supplier_other',
            'breeding_aistrawtype',
            'breeding_aistrawid',
            'breeding_aisirecountry',
            'breeding_aisirebreed',
            'breeding_aisirebreedoth',
            'breeding_aibreedcomposition',
            'breeding_aisemenbatch',
            'breeding_ai_payment_modes',
            'breeding_aicost',
            'breeding_ai_is_subsidised',
            'breeding_ai_voucher',
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
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_AI, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }

    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_AI;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['lactation_id', 'lactation_number'];
    }
}