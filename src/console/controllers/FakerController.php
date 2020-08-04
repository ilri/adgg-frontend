<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-05-23 8:00 PM
 */

namespace console\controllers;


use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use common\helpers\ArrayHelper;
use common\models\ActiveRecord;
use yii\console\Controller;

class FakerController extends Controller
{
    public function actionTest()
    {
        //\console\jobs\ODKFormProcessor::push(['itemId' => 7794]);
        //$this->resetModels(Animal::class);
        $this->setFarmLocationDetails();
        //\console\jobs\ODKFormProcessor::push(['itemId' => 8494]);
        // $this->resetAnimals();
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
        $totalRecords = Animal::getCount($condition, $params);
        $modelClassName = Animal::class;
        $n = 1;
        $choices = Choices::getListData('value', 'label', false, ['list_type_id' => ChoiceTypes::CHOICE_TYPE_ANIMAL_COLORS]);
        /* @var $models Animal[] */
        foreach ($query->batch(1000) as $i => $models) {
            if ($n < 300000) {
                $n += 1000;
                continue;
            }
            foreach ($models as $model) {
                if (empty($model->color) && empty($model->secondary_breed)) {
                    $this->stdout("{$modelClassName}: Record {$n} of {$totalRecords} records has empty secondary breed and color. Ignored\n");
                    $n++;
                    continue;
                }

                if (!empty($model->color) && !is_array($model->color)) {
                    $color = trim($model->color);
                    $colorInt = ArrayHelper::arraySearchCaseInsensitive($color, $choices);
                    if ($colorInt) {
                        //get the value
                        $model->color = [$colorInt];
                    } else {
                        //set the color_other value
                        $model->color_other = $color;
                        $model->color = ['-66'];
                    }
                }

                if (!empty($model->secondary_breed) && !is_array($model->secondary_breed)) {
                    $model->second_breed = [$model->secondary_breed];
                }

                $model->save(false);
                $this->stdout("{$modelClassName}: Updated {$n} of {$totalRecords} records\n");
                $n++;
            }
        }
    }
}