<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-05-23 8:00 PM
 */

namespace console\controllers;


use backend\modules\core\models\Animal;
use common\models\ActiveRecord;
use console\jobs\ODKFormProcessor;
use yii\console\Controller;

class FakerController extends Controller
{
    public function actionTest()
    {
        ODKFormProcessor::push(['itemId' => 17720]);
        //\console\jobs\ODKFormProcessor::push(['itemId' => 7794]);
        //$this->resetModels(Animal::class);
        //$this->setFarmLocationDetails();
        //\console\jobs\ODKFormProcessor::push(['itemId' => 8494]);
        //$this->resetAnimals();
    }

    protected function setFarmLocationDetails()
    {
        $condition = '[[region_id]] IS NOT NULL';
        $params = [];
        $query = Animal::find()->andWhere($condition, $params);
        $totalRecords = $query->count();
        $n = 1;
        $modelClassName = Animal::class;
        /* @var $models Animal[] */
        foreach ($query->batch(1000) as $i => $models) {
            foreach ($models as $model) {
                if ($model->farm !== null) {
                    if (empty($model->farm->country_id)) {
                        $model->farm->country_id = $model->country_id;
                    }
                    if (empty($model->farm->region_id)) {
                        $model->farm->region_id = $model->region_id;
                    }
                    if (empty($model->farm->district_id)) {
                        $model->farm->district_id = $model->district_id;
                    }
                    if (empty($model->farm->ward_id)) {
                        $model->farm->ward_id = $model->ward_id;
                    }
                    if (empty($model->farm->village_id)) {
                        $model->farm->village_id = $model->village_id;
                    }
                    if (empty($model->farm->org_id)) {
                        $model->farm->org_id = $model->org_id;
                    }
                    if (empty($model->farm->client_id)) {
                        $model->farm->client_id = $model->client_id;
                    }
                    $model->farm->save(false);
                }
                $this->stdout("{$modelClassName}: processed record {$n} of {$totalRecords} records\n");
                $n++;
            }
        }
    }

    /**
     * @param string $modelClassName
     * @param string|array $condition
     * @param array $params
     * @throws \Exception
     */
    protected function resetModels($modelClassName, $condition = '', $params = [])
    {
        $query = $modelClassName::find()->andWhere($condition, $params);
        $totalRecords = $modelClassName::getCount($condition, $params);
        $n = 1;
        /* @var $models ActiveRecord[] */
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                $model->save(false);
                $this->stdout("{$modelClassName}: Updated {$n} of {$totalRecords} records\n");
                $n++;
            }
        }
    }

    protected function resetAnimals()
    {
        $condition = [];
        $params = [];
        $query = Animal::find()->andWhere($condition, $params);
        $totalRecords = $query->count();
        $modelClassName = Animal::class;
        $n = 1;
        /* @var $models Animal[] */
        foreach ($query->batch(1000) as $i => $models) {
            foreach ($models as $model) {
                $model->save(false);
                $this->stdout("{$modelClassName}: Updated {$n} of {$totalRecords} records\n");
                $n++;
            }
        }
    }
}