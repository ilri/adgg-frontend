<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-02-18
 * Time: 12:10 PM
 */

namespace console\controllers;


use console\dataMigration\ke\models\Bulls;
use console\dataMigration\ke\models\Clients;
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
        Clients::migrateData();
    }

    protected function migrateKeBulls()
    {
        $query = Bulls::find()->andWhere([]);
        /* @var $models Bulls[] */
        $n = 1;
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $this->stdout("Bulls: Processing Record {$n}\n");
                $n++;
            }
        }
    }
}