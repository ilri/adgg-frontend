<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Animal;

class AnimalsController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = Animal::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex($farm_id = null, $tag_id = null, $animal_type = null, $breed = null)
    {
        $searchModel = Animal::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'enablePagination' => true,
            'pageSize' => SystemSettings::getPaginationSize(),
            'with' => ['farm']
        ]);
        $searchModel->farm_id = $farm_id;
        $searchModel->tag_id = $tag_id;
        $searchModel->animal_type = $animal_type;
        $searchModel->main_breed = $breed;
        return $searchModel->search();
    }
}