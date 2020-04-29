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
     * @param bool $validate
     */
    protected static function saveModel(ActiveRecord $model, $n = null, $validate = true)
    {
        $saved = $model->save($validate);
        $className = get_class($model);
        $orgName = static::getOrgName();
        if ($saved) {
            Yii::$app->controller->stdout($orgName . ': ' . $className . ": saved record {$n} successfully\n");
        } else {
            $error = json_encode($model->getErrors());
            Yii::$app->controller->stdout($orgName . ": Validation Error on record {$n}: {$error}\n");
        }
    }
}