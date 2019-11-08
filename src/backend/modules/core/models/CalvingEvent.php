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
            'bull_id',
            'calfdeformities',
            'calfweight',
            'calfhgirth',
            'calfbodyscore',
            'intuse',
            'calvdatedead',
            'whydead',
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