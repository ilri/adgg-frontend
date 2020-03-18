<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2015/12/07
 * Time: 7:53 PM
 */

namespace console\controllers;


use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\MilkingEvent;

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
        $condition = '[[event_type]]=:event_type AND [[migration_id]] IS NOT NULL';
        $params = [':event_type' => AnimalEvent::EVENT_TYPE_CALVING];
        $query = CalvingEvent::find()->andWhere($condition, $params);
        $n = 1;
        /* @var $models CalvingEvent[] */
        $className = CalvingEvent::class;
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $model->save(false);
                //$this->stdout("{$className}: Updated {$n} records\n");
                $n++;
            }
        }

        $condition = '[[event_type]]=:event_type AND [[migration_id]] IS NOT NULL';
        $params = [':event_type' => AnimalEvent::EVENT_TYPE_MILKING];
        $query = MilkingEvent::find()->andWhere($condition, $params);
        $n = 1;
        /* @var $models MilkingEvent[] */
        $className = CalvingEvent::class;
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $model->save(false);
                // $this->stdout("{$className}: Updated {$n} records\n");
                $n++;
            }
        }

    }
}