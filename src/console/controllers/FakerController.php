<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-05-23 8:00 PM
 */

namespace console\controllers;


use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\MilkingEvent;
use console\jobs\ODKFormProcessor;
use yii\console\Controller;

class FakerController extends Controller
{
    public function actionResetMilkingModels()
    {
        $condition = ['event_type' => AnimalEvent::EVENT_TYPE_MILKING];
        $query = MilkingEvent::find()->andWhere($condition);
        $totalMilkRecords = MilkingEvent::getCount($condition);
        $n = 1;
        /* @var $models MilkingEvent[] */
        $className = MilkingEvent::class;
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $model->save(false);
                $this->stdout("{$className}: Updated {$n} of {$totalMilkRecords} records\n");
                $n++;
            }
        }
    }

    public function actionRandom()
    {
        ODKFormProcessor::push(['itemId' => 7794]);
    }
}