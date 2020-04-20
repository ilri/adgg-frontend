<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-02-18
 * Time: 12:10 PM
 */

namespace console\controllers;


use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\MilkingEvent;
use console\dataMigration\ke\models\Bulls;
use console\dataMigration\ke\models\Cows;
use console\dataMigration\ke\models\Cowtests;
use console\dataMigration\ke\models\Farms;
use console\dataMigration\ke\models\Herds;
use console\dataMigration\ke\models\Lacts;
use yii\console\Controller;

class DataMigrationController extends Controller
{
    public function actionRun()
    {
        $time_start = microtime(true);
        $this->doMigration();
        $time_end = microtime(true);
        $executionTime = round($time_end - $time_start, 2);
        $this->stdout("KE KLBA DATA MIGRATION EXECUTED IN {$executionTime} SECONDS\n");
    }

    protected function doMigration()
    {
        $this->doKeMigration();
    }

    protected function doKeMigration()
    {
        //Clients::migrateData();
        /*Farms::migrateData();
        Herds::migrateData();
        Cows::migrateData();
        Bulls::migrateData();
        Lacts::migrateData();
        */
        Cowtests::migrateData();
        Cows::updateSiresAndDams();
        $this->updateCalvingRecords();
        $this->updateMilkingRecords();
    }

    protected function updateCalvingRecords()
    {
        $condition = '[[event_type]]=:event_type AND [[migration_id]] IS NOT NULL';
        $params = [':event_type' => AnimalEvent::EVENT_TYPE_CALVING];
        $query = CalvingEvent::find()->andWhere($condition, $params);
        $n = 1;
        /* @var $models CalvingEvent[] */
        $className = CalvingEvent::class;
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $model->save(false);
                $this->stdout("{$className}: Updated {$n} records\n");
                $n++;
            }
        }
    }

    protected function updateMilkingRecords()
    {
        $condition = '[[event_type]]=:event_type AND [[migration_id]] IS NOT NULL';
        $params = [':event_type' => AnimalEvent::EVENT_TYPE_MILKING];
        $query = MilkingEvent::find()->andWhere($condition, $params);
        $n = 1;
        /* @var $models MilkingEvent[] */
        $className = MilkingEvent::class;
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $model->save(false);
                $this->stdout("{$className}: Updated {$n} records\n");
                $n++;
            }
        }
    }
}