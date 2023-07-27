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
 * Class CalvingEvent
 * @package backend\modules\core\models
 *
 * @property string $calvtype
 * @property string $easecalv
 * @property string $birthtyp
 * @property string $calfsex
 * @property string $calfsiretype
 * @property string $aiprov
 * @property string $aiprov_other
 * @property string|array $calfdeformities
 * @property string $intuse
 *
 */
class CalvingEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [$this->getExcelColumns(), 'safe', 'on' => self::SCENARIO_UPLOAD],
            ['event_date', 'validateCalvingDate'],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'event_date' => 'Calving Date',
        ]);
    }

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'animal_id',
            'field_agent_id',
            'event_date',
            'calvtype',
            'easecalv',
            'birthtyp',
            'calfsex',
            'calfsiretype',
            'aiprov',
            'aiprov_other',
            'intuse',
            'intuseoth',
            'calfdeformities',
            'calfdeformitiesoth',
            'calfweightknown',
            'calvdatedead',
            'whydead',
            'whydeadoth',
            'calfname',
            'calftagprefix',
            'calftagsec',
//            'tag_id',
            'calfcolor',
            'calfweight',
            'calfbodyscore',
            'calfhgirth',
            'calftagimage',
            'calfbodyimage',
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
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_CALVING, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }

    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_CALVING;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['animal_id', 'bull_id'];
    }
}