<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-05-23 8:00 PM
 */

namespace console\controllers;


use backend\modules\core\models\Animal;
use console\jobs\ProcessODKJson;
use Yii;
use yii\console\Controller;

class FakerController extends Controller
{
    public function actionRun()
    {
        $time_start = microtime(true);
        $this->loadFakeData();
        $time_end = microtime(true);
        $executionTime = round($time_end - $time_start, 2);
        $this->stdout("FAKER EXECUTED IN {$executionTime} SECONDS\n");
    }

    public function actionClear()
    {
        $this->canExecuteFaker();
    }

    public function actionReset()
    {
        $time_start = microtime(true);
        $time_end = microtime(true);
        $executionTime = round($time_end - $time_start, 2);
        $this->stdout("FAKER EXECUTED IN {$executionTime} SECONDS\n");
    }

    protected function loadFakeData()
    {
        $this->canExecuteFaker();
    }

    protected function canExecuteFaker()
    {
        if (YII_ENV === 'prod') {
            $this->stdout("FAKER CANNOT BE EXECUTED\n");
            Yii::$app->end();
        } else {
            $this->stdout("FAKER CANNOT BE EXECUTED\n");
            Yii::$app->end();
        }
    }

    public function actionResetCalvingModels()
    {
        $condition = [];
        $query = Animal::find()->andWhere($condition);
        $totalAnimals = Animal::getCount($condition);
        $n = 1;
        /* @var $models Animal[] */
        $className = Animal::class;
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $model->save(false);
                $this->stdout("{$className}: Updated {$n} of {$totalAnimals} records\n");
                $n++;
            }
        }
    }

    public function actionRandom()
    {
        ProcessODKJson::push(['queueId' => 7794]);
    }
}