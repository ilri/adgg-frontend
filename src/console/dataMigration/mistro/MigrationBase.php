<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-02-20
 * Time: 2:48 AM
 */

namespace console\dataMigration\mistro;


use common\models\ActiveRecord;
use Yii;

/**
 * Class MigrationBase
 * @package console\dataMigration\mistro
 * @method static getOrgName()
 */
abstract class MigrationBase extends ActiveRecord
{
    /**
     * @param ActiveRecord $model
     * @param null $n
     * @param int|null $totalRecords
     * @param bool $validate
     * @return ActiveRecord
     */
    protected static function saveModel(ActiveRecord $model, $n = null, $totalRecords = null, $validate = true)
    {
        $saved = $model->save($validate);
        $className = get_class($model);
        $prefix = static::getMigrationIdPrefix();
        if ($saved) {
            Yii::$app->controller->stdout($prefix . ': ' . $className . ": saved record {$n} of {$totalRecords} successfully\n");
        } else {
            $error = json_encode($model->getErrors());
            Yii::$app->controller->stdout($prefix . ": " . $className . ": Validation Error on record {$n} of {$totalRecords}: {$error}\n");
        }
        return $model;
    }
}