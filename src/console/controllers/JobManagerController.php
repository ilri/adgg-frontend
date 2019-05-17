<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2015/12/07
 * Time: 7:53 PM
 */

namespace console\controllers;


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
        \backend\modules\saving\models\WithdrawNotice::checkDueDate();
    }

    public function actionNotification()
    {
        $this->startJob('notificationCron');
    }

    protected function notificationCron()
    {
        \backend\modules\conf\models\Notif::createNotifications();
    }
}