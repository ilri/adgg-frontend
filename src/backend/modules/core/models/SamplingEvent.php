<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-15
 * Time: 8:49 AM
 */

namespace backend\modules\core\models;


use common\excel\ImportActiveRecordInterface;
use common\helpers\DbUtils;
use yii\helpers\ArrayHelper;


/**
 * Class HairsamplingEvent
 * @package backend\modules\core\models
 *
 * @property string $hair_sampling_code
 * @property float $Bos_Indicus
 * @property float $Ndama
 * @property float $AYR
 * @property float $BF
 * @property float $GUE
 * @property float $HOL
 * @property float $JER
 */
class SamplingEvent extends AnimalEvent implements ImportActiveRecordInterface, AnimalEventInterface
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
            'event_date' => 'Sampling Date',
        ]);
    }

    /**
     * @return string[]
     */
    public function getExcelColumns()
    {
        return [
            'animal_id',
            'event_date',
            'hair_sampling_code',
            'Bos_Indicus',
            'Ndama',
            'AYR',
            'BF	',
            'GUE',
            'HOL',
            'JER',
        ];
    }

    /**
     * @param int $durationType
     * @param false $sum
     * @param array $filters
     * @param string $dateField
     * @param null $from
     * @param null $to
     * @param string $condition
     * @param array $params
     * @return int
     * @throws \Exception
     */
    public static function getDashboardStats($durationType, $sum = false, $filters = [], $dateField = 'event_date', $from = null, $to = null, $condition = '', $params = [])
    {
        list($condition, $params) = DbUtils::appendCondition('event_type', self::EVENT_TYPE_SAMPLING, $condition, $params);
        return parent::getDashboardStats($durationType, $sum, $filters, $dateField, $from, $to, $condition, $params);
    }

    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_SAMPLING;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['lactation_id', 'lactation_number'];
    }
}