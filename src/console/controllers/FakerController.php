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
        //$this->resetModels(\backend\modules\core\models\MilkingEvent::class, '[[lactation_id]] IS NOT NULL AND [[event_type]]=2');
        //\console\jobs\ODKFormProcessor::push(['itemId' => 8494]);
        $this->resetAnimals();
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
                if (!empty($model->dim)) {
                    $this->stdout("{$modelClassName}: Record {$n} of {$totalRecords} records has DIM. Ignored\n");
                    $n++;
                    continue;
                }
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
        foreach ($query->batch() as $i => $models) {
            foreach ($models as $model) {
                if (empty($model->color) && empty($model->secondary_breed)) {
                    $this->stdout("{$modelClassName}: Record {$n} of {$totalRecords} records has empty secondary breed and color. Ignored\n");
                    $n++;
                    continue;
                }

                if (!empty($model->color)) {
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

                if (!empty($model->secondary_breed)) {
                    $model->second_breed = [$model->secondary_breed];
                }

                $model->save(false);
                $this->stdout("{$modelClassName}: Updated {$n} of {$totalRecords} records\n");
                $n++;
            }
        }
    }
}