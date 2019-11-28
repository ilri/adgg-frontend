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
use backend\modules\core\models\Farm;

class FarmersController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = Farm::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex()
    {
        list($condition, $params) = Farm::appendOrgSessionIdCondition('', [], false);
        $searchModel = Farm::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'enablePagination' => true,
            'limit' => 50,
        ]);

        return $searchModel->search();
    }
}