<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2015/12/07
 * Time: 7:53 PM
 */

namespace console\controllers;


use console\dataMigration\ke\models\Cows;
use console\dataMigration\ke\models\Cowtests;
use console\dataMigration\ke\models\Herds;
use console\dataMigration\ke\models\Lacts;
use console\dataMigration\ke\models\Testdays;

/**
 * Runs all the system jobs (daemon and cronjobs)
 *
 * @author Fred <mconyango@gmail.com>
 */
class JobManagerController extends BaseController
{
    public function actionGeneral()
    {
        $this->startJob('generalCron');
    }

    protected function generalCron()
    {
    }

    public function actionNotification()
    {
        $this->startJob('notificationCron');
    }

    protected function notificationCron()
    {
        \backend\modules\conf\models\Notif::createNotifications();
    }

    public function actionDataMigration()
    {
        $this->startJob('dataMigration');
    }

    protected function dataMigration()
    {
        //Clients::migrateData();
        //Farms::migrateData();
        //Herds::migrateData();
        //Cows::migrateData();
        //Cows::updateSiresAndDams();
        Lacts::migrateData();
        Cowtests::migrateData();
    }
}