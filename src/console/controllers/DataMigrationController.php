<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-02-18
 * Time: 12:10 PM
 */

namespace console\controllers;


use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\AnimalHerd;
use backend\modules\core\models\Farm;
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
        \console\dataMigration\mistro\stanley1\Migrate::run();
        \console\dataMigration\mistro\stanley2\Migrate::run();
        \console\dataMigration\mistro\kalro\Migrate::run();
        \console\dataMigration\mistro\klba\Migrate::run();
    }

    /**
     * This function updates location data of herds, animals, animal events each farm
     */
    public function actionUpdateFarmsLocation()
    {
        $condition = '';
        $params = [];
        $query = Farm::find()->andWhere($condition, $params);
        $totalRecords = $query->count();
        $n = 1;
        $modelClassName = Farm::class;
        /* @var $models Farm[] */
        $limit = 1000;
        foreach ($query->batch($limit) as $i => $models) {
            foreach ($models as $model) {
                $updateFields = [
                    'country_id' => $model->country_id,
                    'region_id' => $model->region_id,
                    'district_id' => $model->district_id,
                    'ward_id' => $model->ward_id,
                    'village_id' => $model->village_id,
                    'org_id' => $model->org_id,
                    'client_id' => $model->client_id,
                ];
                $updateCondition = ['farm_id' => $model->id];
                //update herds
                AnimalHerd::updateAll($updateFields, $updateCondition);
                //update animals
                Animal::updateAll($updateFields, $updateCondition);
                //update animals events
                $animalIds = Animal::getColumnData('id', $updateCondition);
                if (!empty($animalIds)) {
                    AnimalEvent::updateAll($updateFields, ['animal_id' => $animalIds]);
                }

                $this->stdout("{$modelClassName}: updated location data (of herds, animals and animal events) of farm record {$n} of {$totalRecords} records\n");
                $n++;
            }
        }
    }

    protected function actionUpdateAnimalWeightEventsFromMilkEvent()
    {
        $query = AnimalEvent::find()->andWhere(['event_type' => AnimalEvent::EVENT_TYPE_MILKING]);
        $totalRecords = $query->count();
        $modelClassName = Animal::class;
        $n = 1;
        /* @var $models AnimalEvent[] */
        $weightModel = new AnimalEvent([
            'event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS,
        ]);
        foreach ($query->batch(1000) as $i => $models) {
            foreach ($models as $model) {
                if (!empty($model->weight) || !empty($model->milk_estimated_weight)) {
                    $newWeightModel = clone $weightModel;
                    $newWeightModel->animal_id = $model->animal_id;
                    $newWeightModel->event_date = $model->event_date;
                    $newWeightModel->data_collection_date = $model->data_collection_date;
                    $newWeightModel->weight_kg = $model->weight;
                    $newWeightModel->estimated_weight = $model->milk_estimated_weight;
                    $newWeightModel->body_score = $model->milk_bodyscore;
                    $newWeightModel->heartgirth = $model->milk_heartgirth;
                    $newWeightModel->latitude = $model->latitude;
                    $newWeightModel->longitude = $model->longitude;
                    $newWeightModel->field_agent_id = $model->field_agent_id;
                    $newWeightModel->migration_id = $model->migration_id;
                    $newWeightModel->odk_form_uuid = $model->odk_form_uuid;
                    $newWeightModel->save(false);
                }

                $this->stdout("{$modelClassName}: Updated {$n} of {$totalRecords} records\n");
                $n++;
            }
        }
    }
}