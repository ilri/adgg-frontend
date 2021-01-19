<?php


namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\auth\Session;
use backend\modules\core\models\CountriesDashboardStats;

class SmsFeedbackController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = CountriesDashboardStats::class;

    public function init()
    {
        parent::init();
    }

    public function actionIndex($lactationNumber=null){
        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()) {
            return CountriesDashboardStats::getSmsFeedback($lactationNumber);
        } else {
            throw new ForbiddenHttpException("Not allowed to access this page");

        }
    }


}