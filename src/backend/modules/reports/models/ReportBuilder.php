<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-20
 * Time: 9:40 AM
 */

namespace backend\modules\reports\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use common\helpers\DbUtils;
use common\helpers\Str;
use yii\base\Model;

class ReportBuilder extends Model
{
    public static function reportableModels(){
        return [
            'Farm' => [
                'class' => Farm::class,
                'relations' => ['fieldAgent'],
            ],
            'Animal' => [
                'class' => Animal::class,
                'relations' => ['farm', 'herd', 'sire', 'dam'],
            ],
        ];
    }

    public static function buildAttributeList(){

    }
}