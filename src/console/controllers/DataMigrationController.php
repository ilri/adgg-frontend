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
use console\dataMigration\ke\models\Cows;
use console\dataMigration\ke\models\Farms;
use console\dataMigration\ke\models\Herds;
use console\dataMigration\ke\models\Lacts;
use console\dataMigration\ke\models\Testdays;
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
        //Farms::migrateData();
        //Herds::migrateData();
        //Cows::migrateData();
        //Bulls::migrateData();
        Lacts::migrateData();
        Testdays::migrateData();
    }
}