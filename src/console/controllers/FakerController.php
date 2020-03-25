<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-05-23 8:00 PM
 */

namespace console\controllers;


use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\MilkingEvent;
use backend\modules\core\models\OdkJsonQueue;
use backend\modules\core\models\Country;
use common\helpers\FileManager;
use console\dataMigration\ke\models\Cows;
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
        $this->clearFakeData();
    }

    public function actionReset()
    {
        $time_start = microtime(true);
        //$this->resetAttributeValues(Users::class, UserAttributeValue::class, 'user_id');
        //$this->resetAttributeValues(Farm::class, FarmAttributeValue::class, 'farm_id');
        //$this->resetAttributeValues(Animal::class, AnimalAttributeValue::class, 'animal_id');
        //$this->resetAttributeValues(AnimalEvent::class, AnimalEventValue::class, 'event_id');
        $time_end = microtime(true);
        $executionTime = round($time_end - $time_start, 2);
        $this->stdout("FAKER EXECUTED IN {$executionTime} SECONDS\n");
    }

    protected function resetAttributeValues($primaryModelClass, $secondaryModelClass, $foreignKeyAttribute)
    {
        $query = $primaryModelClass::find()->andWhere([]);
        $n = 1;
        foreach ($query->batch(1000) as $i => $models) {
            foreach ($models as $model) {
                $this->stdout("{$primaryModelClass}: Processing Record {$n}\n");
                $attributes = [];
                $data = $secondaryModelClass::getData(['attribute_id', 'attribute_value', 'attribute_value_json'], [$foreignKeyAttribute => $model->id]);
                foreach ($data as $row) {
                    if (!empty($row['attribute_value'])) {
                        $attributes[$row['attribute_id']] = $row['attribute_value'];
                    } elseif (!empty($row['attribute_value_json'])) {
                        $attributes[$row['attribute_id']] = json_decode($row['attribute_value_json'], true);
                    }
                }
                $model->additional_attributes = $attributes;
                if (!empty($model->additional_attributes)) {
                    $model->save(false);
                }
                $n++;
            }
        }
    }

    protected function loadFakeData()
    {
        $this->canExecuteFaker();
        $this->clearFakeData();
    }

    protected function clearFakeData()
    {
        $sql = "SET FOREIGN_KEY_CHECKS=0;";
        //$sql .= "TRUNCATE  `sys_queue`;TRUNCATE  `sys_app_session`;TRUNCATE `auth_log`;";
        //$sql .= "TRUNCATE " . SmsOutbox::tableName() . ";";
        //$sql .= "TRUNCATE " . EmailOutbox::tableName() . ";";
        //$sql .= "TRUNCATE " . OrganizationRefUnits::tableName() . ";";
        //$sql .= "TRUNCATE " . ExcelImport::tableName() . ";";
        //$sql .= "TRUNCATE " . OdkJsonQueue::tableName() . ";";
        //$sql .= "TRUNCATE " . Notif::tableName() . ";";
        //$sql .= "TRUNCATE " . NotifQueue::tableName() . ";";
        //$sql .= "TRUNCATE " . AuditTrail::tableName() . ";";
        $sql .= "TRUNCATE " . AnimalEvent::tableName() . ";";
        //$sql .= "TRUNCATE " . Animal::tableName() . ";";
        //$sql .= "TRUNCATE " . AnimalHerd::tableName() . ";";
        //$sql .= "TRUNCATE " . Farm::tableName() . ";";

        //$sql .= "UPDATE " . NumberingFormat::tableName() . " SET [[next_number]]=1 WHERE [[id]]=:OrganizationRef_account_no;";
        $sql .= "SET FOREIGN_KEY_CHECKS=1;";
        Yii::$app->db->createCommand($sql, [])->execute();
    }

    protected function canExecuteFaker()
    {
        if (YII_ENV === 'prod') {
            $this->stdout("FAKER CANNOT BE EXECUTED\n");
            Yii::$app->end();
        }
    }

    public function actionResetMilkingModels()
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

    public function actionResetCalvingModels()
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

    public function actionRandom()
    {
        $condition = '';
        $params = [];
        $query = OdkJsonQueue::find()->andWhere($condition, $params);
        $n = 1;
        /* @var $models OdkJsonQueue[] */
        $className = OdkJsonQueue::class;
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $model->save(false);
                $this->stdout("{$className}: Updated {$n} records\n");
                $n++;
            }
        }
    }
}