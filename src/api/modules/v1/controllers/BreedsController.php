<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;

class BreedsController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = Choices::class;

    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        return Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
    }
}