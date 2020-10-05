<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-10-05
 * Time: 8:18 PM
 */

namespace backend\modules\core\models;


class AnimalUpdate extends Animal
{
    /**
     * @return array
     */
    public function getExcelColumns()
    {
        return [
            'tag_id',
            'birthdate',
        ];
    }
}