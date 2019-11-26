<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\core\models\Animal;

class AnimalsController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = Animal::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex()
    {
        list($condition, $params) = Animal::appendOrgSessionIdCondition('', [], false);
        $searchModel = Animal::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'enablePagination' => true,
        ]);
        return $searchModel->search();
    }
}