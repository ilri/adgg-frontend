<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-02-18
 * Time: 12:10 PM
 */

namespace console\controllers;


use yii\console\Controller;

class DataMigrationController extends Controller
{
    public function actionRun()
    {
        $time_start = microtime(true);
        $this->doMigration();
        $time_end = microtime(true);
        $executionTime = round($time_end - $time_start, 2);
        $this->stdout("DATA MIGRATION TASK EXECUTED IN {$executionTime} SECONDS\n");
    }

    protected function doMigration()
    {
        //\console\dataMigration\mistro\stanley1\Migrate::run();
        //\console\dataMigration\mistro\stanley2\Migrate::run();
        \console\dataMigration\mistro\kalro\Migrate::run();
        //\console\dataMigration\mistro\klba\Migrate::run();
    }
}