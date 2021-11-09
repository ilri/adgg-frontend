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
use backend\modules\core\models\OdkForm;
use console\jobs\ODKFormProcessor;
use yii\console\Controller;

class DataMigrationController extends Controller
{
    public function actionRun()
    {
        $time_start = microtime(true);
        //$this->doMigration();
        $this->requeueStalledOdkForms();
        $time_end = microtime(true);
        $executionTime = round($time_end - $time_start, 2);
        $this->stdout("DATA MIGRATION TASK EXECUTED IN {$executionTime} SECONDS\n");
    }

    protected function requeueStalledOdkForms()
    {
        $query = OdkForm::find()->andWhere(['is_processed' => 0]);
        $modelClassName = OdkForm::class;
        $totalRecords = $query->count();
        $n = 1;
        foreach ($query->batch(100) as $i => $models) {
            foreach ($models as $model) {
                ODKFormProcessor::push(['itemId' => $model->id]);
                $this->stdout("{$modelClassName}: processed {$n} of {$totalRecords} records\n");
                $n++;
            }
        }
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

    public function actionUpdateAnimalWeightEventsFromMilkEvent()
    {
        $query = AnimalEvent::find()->andWhere(['event_type' => AnimalEvent::EVENT_TYPE_MILKING]);
        $totalRecords = $query->count();
        $modelClassName = AnimalEvent::class;
        $n = 1;
        /* @var $models AnimalEvent[] */
        $weightModel = new AnimalEvent([
            'event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS,
        ]);
        foreach ($query->batch(1000) as $i => $models) {
            foreach ($models as $model) {
                if (empty($model->weight) && empty($model->milk_estimated_weight) && empty($model->milk_bodyscore) && empty($model->milk_heartgirth)) {
                    $this->stdout("{$modelClassName}: Ignored record {$n} of {$totalRecords}. No weight data\n");
                } else {
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
                    $this->stdout("{$modelClassName}: Updated weight event {$n} of {$totalRecords} records\n");
                }
                $n++;
            }
        }
    }

    public function actionUpdateAnimalWeightEventsFromCalvingEvent()
    {
        $query = AnimalEvent::find()->andWhere(['event_type' => AnimalEvent::EVENT_TYPE_CALVING]);
        $totalRecords = $query->count();
        $modelClassName = AnimalEvent::class;
        $n = 1;
        /* @var $models AnimalEvent[] */
        $weightModel = new AnimalEvent([
            'event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS,
        ]);
        foreach ($query->batch(1000) as $i => $models) {
            foreach ($models as $model) {
                if (empty($model->calfweight) && empty($model->calfhgirth) && empty($model->calfbodyscore)) {
                    $this->stdout("{$modelClassName}: Ignored record {$n} of {$totalRecords}. No weight data\n");
                } else {
                    $animalId = Animal::getScalar('id', ['tag_id' => $model->tag_id]);
                    if (empty($animalId)) {
                        $this->stdout("{$modelClassName}: Ignored record {$n} of {$totalRecords}. Calf not registered\n");
                        $n++;
                        continue;
                    }
                    $newWeightModel = clone $weightModel;
                    $newWeightModel->animal_id = $animalId;
                    $newWeightModel->event_date = $model->event_date;
                    $newWeightModel->data_collection_date = $model->data_collection_date;
                    $newWeightModel->weight_kg = $model->calfweight;
                    $newWeightModel->body_score = $model->calfbodyscore;
                    $newWeightModel->heartgirth = $model->calfhgirth;
                    $newWeightModel->latitude = $model->latitude;
                    $newWeightModel->longitude = $model->longitude;
                    $newWeightModel->field_agent_id = $model->field_agent_id;
                    $newWeightModel->migration_id = $model->migration_id;
                    $newWeightModel->odk_form_uuid = $model->odk_form_uuid;
                    $newWeightModel->save(false);
                    $this->stdout("{$modelClassName}: Updated weight event {$n} of {$totalRecords} records\n");
                }
                $n++;
            }
        }
    }

    public function actionReprocessFailedOdkForms()
    {
        $condition = ['has_errors' => 1, 'is_processed' => 1];
        $query = OdkForm::find()->andWhere($condition);
        $totalRecords = $query->count();
        $n = 1;
        $modelClassName = OdkForm::class;
        /* @var $models OdkForm[] */
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $model->save(false);
                OdkForm::updateAll(['has_errors' => 0, 'is_processed' => 0], ['id' => $model->id]);
                ODKFormProcessor::push(['itemId' => $model->id]);
                $this->stdout("{$modelClassName}: queued {$n} of {$totalRecords} records\n");
                $n++;
            }
        }
    }
}