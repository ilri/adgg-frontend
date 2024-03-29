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
class WeightEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'animal_id',
            'event_date',
            'weight_kg',
            'heartgirth',
            'body_length',
            'body_score',
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
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_WEIGHTS, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }

    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_WEIGHTS;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['lactation_id', 'lactation_number'];
    }
}