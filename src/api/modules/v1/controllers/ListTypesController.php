<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\ChoiceTypes;

class ListTypesController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = ChoiceTypes::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex()
    {
        $searchModel = ChoiceTypes::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'pageSize' => SystemSettings::getPaginationSize(),
            'enablePagination' => true,
        ]);

        return $searchModel->search();
    }
}