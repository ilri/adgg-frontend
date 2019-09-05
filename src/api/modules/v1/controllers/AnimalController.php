<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-09-04
 * Time: 2:42 PM
 */

namespace api\modules\v1\controllers;


use backend\modules\core\models\Animal;

class AnimalController extends ActiveController
{
    public function init()
    {
        $this->modelClass = Animal::class;
        parent::init();
    }

    public function getUnAuthenticatedActions()
    {
        return ['index'];
    }
}