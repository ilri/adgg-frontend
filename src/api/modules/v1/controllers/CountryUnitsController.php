<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\CountryUnits;

class CountryUnitsController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = CountryUnits::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex($country_id, $level)
    {
        $searchModel = CountryUnits::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'pageSize' => SystemSettings::getPaginationSize(),
            'enablePagination' => true,
        ]);
        $searchModel->country_id = $country_id;
        $searchModel->level = $level;
        return $searchModel->search();
    }

    public function actionDependentLists($level, $country_id = null, $parent_id = null, $placeholder = false)
    {
        if ($level == CountryUnits::LEVEL_REGION) {
            $data = CountryUnits::getListData('id', 'name', $placeholder, ['country_id' => $country_id, 'level' => $level]);
        } else {
            $data = CountryUnits::getListData('id', 'name', $placeholder, ['parent_id' => $parent_id, 'level' => $level]);
        }
        return $data;
    }

}