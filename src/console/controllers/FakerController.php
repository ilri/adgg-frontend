<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-05-23 8:00 PM
 */

namespace console\controllers;


use common\models\ActiveRecord;
use yii\console\Controller;

class FakerController extends Controller
{
    public function actionTest()
    {
        //\console\jobs\ODKFormProcessor::push(['itemId' => 7794]);
        //$this->resetModels(\backend\modules\core\models\OdkForm::class);
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
}