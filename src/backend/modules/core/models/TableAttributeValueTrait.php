<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-05
 * Time: 3:34 PM
 */

namespace backend\modules\core\models;

/**
 * Trait TableAttributeValueTrait
 * @package backend\modules\core\models
 *
 * @property TableAttribute $tableAttribute
 */
trait TableAttributeValueTrait
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTableAttribute()
    {
        return $this->hasOne(TableAttribute::class, ['id' => 'attribute_id']);
    }
}