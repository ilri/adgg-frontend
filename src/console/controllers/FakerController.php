<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-05-23 8:00 PM
 */

namespace console\controllers;


use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\MilkingEvent;
use backend\modules\core\models\TableAttribute;
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
        //$sql .= "TRUNCATE " . AnimalEvent::tableName() . ";";
        //$sql .= "TRUNCATE " . Animal::tableName() . ";";
        //$sql .= "TRUNCATE " . AnimalHerd::tableName() . ";";
        //$sql .= "TRUNCATE " . Farm::tableName() . ";";

        //$sql .= "UPDATE " . NumberingFormat::tableName() . " SET [[next_number]]=1 WHERE [[id]]=:OrganizationRef_account_no;";
        $sql .= "SET FOREIGN_KEY_CHECKS=1;";
        //Yii::$app->db->createCommand($sql, [])->execute();
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

    public function actionResetAnimalAttributes()
    {
        //add additional attributes
        $tableName = TableAttribute::tableName();
        $sql = "INSERT INTO {$tableName} ([[id]],[[attribute_key]], [[attribute_label]], [[table_id]], [[group_id]], [[input_type]], [[default_value]], [[list_type_id]], [[event_type]], [[is_active]], [[is_alias]], [[alias_to]], [[farm_metadata_type]], [[created_at]], [[created_by]]) VALUES
    (254,'color2', 'Color', 3, NULL, 1, 'N;', NULL, NULL, 1, 0, NULL, NULL, '2020-05-06 20:14:07', 1),
(253,'entry_date2', 'Entry Date', 3, NULL, 8, 'N;', NULL, NULL, 1, 0, NULL, NULL, '2020-05-06 20:13:37', 1),
(252,'entry_type2', 'Entry Type', 3, NULL, 5, 'N;', 69, NULL, 1, 0, NULL, NULL, '2020-05-06 20:13:05', 1),
(251,'purchase_cost2', 'Purchase Cost', 3, NULL, 2, 'N;', NULL, NULL, 1, 0, NULL, NULL, '2020-05-06 20:11:55', 1),
(250,'is_derived_birthdate2', 'Is Derived Birthdate', 3, NULL, 4, 'N;', NULL, NULL, 1, 0, NULL, NULL, '2020-05-06 20:04:04', 1),
(249,'deformities2', 'Deformities', 3, NULL, 6, 'N;', 11, NULL, 1, 0, NULL, NULL, '2020-05-06 20:00:07', 1);";
        Yii::$app->db->createCommand($sql, [])->execute();

        //loop through all animals copying the old attributes to new defined attributes in additional attributes
        $condition = [];
        $query = Animal::find()->andWhere($condition);
        $totalRecords = Animal::getCount($condition);
        $n = 1;
        /* @var $models Animal[] */
        $className = Animal::class;
        foreach ($query->batch(1000) as $i => $models) {
            foreach ($models as $model) {
                $model->ignoreAdditionalAttributes = false;
                if (!empty($model->bull_straw_id) && empty($model->sire_tag_id)) {
                    $model->sire_tag_id = $model->bull_straw_id;
                }
                if (!empty($model->deformities)) {
                    $model->deformities2 = $model->deformities;
                }
                $model->is_derived_birthdate2 = $model->is_derived_birthdate;
                $model->purchase_cost2 = $model->purchase_cost;
                $model->entry_type2 = $model->entry_type;
                $model->entry_date2 = $model->entry_date;
                $model->color2 = $model->color;
                $model->save(false);
                $this->stdout("{$className}: Updated {$n} of {$totalRecords} records\n");
                $n++;
            }
        }
        $this->stdout("{$className}: Dropping unnecessary columns ...\n");
        //drop unnecessary attributes
        $tableName = Animal::tableName();
        $sql = "ALTER TABLE {$tableName} DROP [[sire_name]],DROP [[dam_name]], DROP [[bull_straw_id]], DROP [[deformities]], DROP [[is_derived_birthdate]], DROP [[purchase_cost]], DROP [[entry_type]], DROP [[entry_date]], DROP [[color]];";
        Yii::$app->db->createCommand($sql, [])->execute();

        $this->stdout("{$className}: Cleaning up ...\n");
        //update additional attributes
        $tableName = TableAttribute::tableName();
        $sql = "UPDATE {$tableName} SET [[attribute_key]] = (CASE [[id]] WHEN 249 THEN 'deformities' WHEN 250 THEN 'is_derived_birthdate' WHEN 251 THEN 'purchase_cost' WHEN 252 THEN 'entry_type' WHEN 253 THEN 'entry_date' WHEN 254 THEN 'color' END) WHERE [[id]] IN(249, 250, 251, 252, 253, 254);";
        Yii::$app->db->createCommand($sql, [])->execute();
    }

    public function actionRandom()
    {
    }
}