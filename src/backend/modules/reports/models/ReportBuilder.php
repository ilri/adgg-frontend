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
use common\helpers\Utils;
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

    public static function fieldConditionOptions($prompt = false)
    {
        $values = [
            '=' => 'Equal To',
            '>' => 'Greater Than',
            '<' => 'Less Than',
            '>=' => 'Greater or Equal To',
            '<=' => 'Less Than or Equal To',
            'LIKE' => 'LIKE',
            'IS NULL' => 'NULL',
            'NOT NULL' => 'NOT NULL',
        ];
        return Utils::appendDropDownListPrompt($values, $prompt);
    }

    public static function buildAttributeList(){

    }
}