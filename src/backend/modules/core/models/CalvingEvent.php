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

/**
 * Class CalvingEvent
 * @package backend\modules\core\models
 *
 */
class CalvingEvent extends AnimalEvent implements ImportActiveRecordInterface
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['event_date', 'validateCalvingDate'],
        ]);
    }

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'animalTagId',
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
            'calfweight',
            'calfhgirth',
            'calfbodyscore',
            'calvdatedead',
            'whydead',
            'whydeadoth',
            'calfname',
            'calftagprefix',
            'calftagsec',
            'tag_id',
            'calfcolor',
            'calftagimage',
            'calfbodyimage',
        ];
    }
}