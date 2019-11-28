<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\core\models\AnimalEvent;

class AnimalEventController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = AnimalEvent::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex()
    {
        $searchModel = AnimalEvent::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'enablePagination' => true,
            'pageSize' => 10,
        ]);

        return $searchModel->search();
    }
}