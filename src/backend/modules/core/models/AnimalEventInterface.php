<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-03-09
 * Time: 11:48 AM
 */

namespace backend\modules\core\models;


interface AnimalEventInterface
{
    /**
     * @return int
     */
    public function getEventType(): int;
}