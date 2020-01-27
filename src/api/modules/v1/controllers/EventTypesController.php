<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\core\models\AnimalEvent;

class EventTypesController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = AnimalEvent::class;

    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        $data = [];
        $types = AnimalEvent::eventTypeOptions();
        foreach ($types as $key => $value) {
            $data[] = [
                'id' => $key,
                'label' => $value,
            ];
        }
        return $data;
    }
}