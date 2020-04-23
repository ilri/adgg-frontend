<?php


namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Client;
use backend\modules\core\models\CountriesDashboardStats;
use backend\modules\core\models\Country;
use backend\modules\core\models\Organization;
use yii\web\ForbiddenHttpException;

class CountriesStatsController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = CountriesDashboardStats::class;

    public function init()
    {
        parent::init();
    }


    public function actionCountriesList($id = null, $name = null)
    {
        $user = \Yii::$app->user->identity;
        if ($id == null) {
            $id = $user->country_id;
        }
        $searchModel = Country::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'pageSize' => SystemSettings::getPaginationSize(),
            'enablePagination' => true,
        ]);
        $searchModel->id = $id;
        $searchModel->name = $name;
        return $searchModel->search();
    }


    public function actionOrganizations($pageSize = null, $country_id = null, $name = null, $id = null)
    {
        $user = \Yii::$app->user->identity;
        if ($pageSize == null) {
            $pageSize = SystemSettings::getPaginationSize();
        }
        if ($id == null) {
            $id = $user->org_id;
        }

        $searchModel = Organization::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'pageSize' => $pageSize,
            'enablePagination' => true,
        ]);
        $searchModel->id = $id;
        $searchModel->name = $name;
        $searchModel->country_id = $country_id;
        return $searchModel->search();
    }


    public function actionClients($pageSize = null, $org_id = null, $country_id = null, $name = null, $id = null)
    {
        $user = \Yii::$app->user->identity;
        if ($pageSize == null) {
            $pageSize = SystemSettings::getPaginationSize();
        }
        if ($id == null) {
            $id = $user->org_id;
        }
        $searchModel = Client::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'pageSize' => $pageSize,
            'enablePagination' => true,
        ]);
        $searchModel->id = $id;
        $searchModel->country_id = $country_id;
        $searchModel->org_id = $org_id;
        $searchModel->name = $name;
        return $searchModel->search();
    }

    /**
     * @param $report_id
     * @param $country_id
     * @return array
     * @throws \Exception
     */
    public function actionCountryReport($report_id, $country_id)
    {
        $user = \Yii::$app->user->identity;
        if ($user->country_id !== null) {
            $country_id = $user->country_id;
        }
        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()) {
            return CountriesDashboardStats::getCountryReports($report_id, $country_id);
        } else {
            throw new ForbiddenHttpException("Not allowed to access this page");

        }
    }
}