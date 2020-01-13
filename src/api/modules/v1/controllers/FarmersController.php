<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-23
 * Time: 3:21 AM
 */

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Farm;

class FarmersController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = Farm::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex($farm_name = null, $farmer_phone = null, $farm_type = null, $project = null)
    {
        $searchModel = Farm::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'pageSize' => SystemSettings::getPaginationSize(),
            'enablePagination' => true,
        ]);
        $searchModel->name = $farm_name;
        $searchModel->phone = $farmer_phone;
        $searchModel->farm_type = $farm_type;
        $searchModel->project = $project;
        return $searchModel->search();
    }
}