<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-01-14
 * Time: 12:56 AM
 */

namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use common\helpers\DbUtils;
use yii\helpers\ArrayHelper;

/**
 * Class PDEvent
 * @package backend\modules\core\models
 *
 * @property string $animalbreeding_pdresult
 * @property string $animalbreeding_pdserviceknown
 * @property string $breeding_pdbodyscore
 * @property string $breeding_pdcost
 * @property string $breeding_pdexamtime
 * @property string $breeding_pdmethod
 * @property string $breeding_pdservicedate
 * @property string $breeding_pdstage
 */
class PDEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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
            'event_date' => 'Examination Date',
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
            'animalbreeding_pdserviceknown',
            'breeding_pdservicedate',
            'event_date',
            'breeding_pdexamtime',
            'animalbreeding_pdresult',
            'breeding_pdstage',
            'breeding_pdbodyscore',
            'breeding_pdcost',
            'breeding_pdmethod',
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
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_PREGNANCY_DIAGNOSIS, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }

    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_PREGNANCY_DIAGNOSIS;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['lactation_id', 'lactation_number'];
    }
}