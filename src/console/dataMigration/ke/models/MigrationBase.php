<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-02-20
 * Time: 2:48 AM
 */

namespace console\dataMigration\ke\models;


use common\models\ActiveRecord;
use Yii;

abstract class MigrationBase extends ActiveRecord
{
    /**
     * @param ActiveRecord $model
     * @param null $n
     */
    protected static function saveModel(ActiveRecord $model, $n = null)
    {
        $saved = $model->save();
        $className = get_class($model);
        if ($saved) {
            Yii::$app->controller->stdout($className . ": saved record {$n} successfully\n");
        } else {
            $error = json_encode($model->getErrors());
            Yii::$app->controller->stdout("Validation Error on record {$n}: {$error}\n");
        }
    }
}