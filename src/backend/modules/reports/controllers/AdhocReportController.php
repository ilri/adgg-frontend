<?php

namespace backend\modules\reports\controllers;

use backend\modules\auth\models\UserLevels;
use backend\modules\auth\Session;
use backend\modules\core\models\Organization;
use backend\modules\reports\Constants;
use backend\modules\reports\models\AdhocReport;
use common\helpers\DateUtils;
use common\models\ActiveRecord;

/**
 * Default controller for the `reports` module
 */
class AdhocReportController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->resourceLabel = 'Ad-hoc Reports';
        $this->resource = Constants::RES_REPORTS;
        $this->activeMenu = Constants::MENU_REPORTS;
    }

    public function actionIndex($org_id = null, $name = null, $created_by = null, $status = null, $from = null, $to = null)
    {
        $orgModel = null;
        if (Session::isOrganization()) {
            $org_id = Session::getOrgId();
            $level_id = UserLevels::LEVEL_COUNTRY;
        }
        if (!empty($org_id)) {
            $orgModel = Organization::loadModel($org_id);
        }
        $date_filter = DateUtils::getDateFilterParams($from, $to, 'created_at', false, false);
        $condition = $date_filter['condition'];
        $params = [];

        $searchModel = AdhocReport::searchModel([
            'defaultOrder' => ['name' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['level', 'role', 'org'],
        ]);
        //$searchModel->org_id = $org_id;
        //$searchModel->status = AdhocReport::STATUS_QUEUED;
        $searchModel->name = $name;
        $searchModel->created_by = $created_by;
        $searchModel->status = $status ?? AdhocReport::STATUS_QUEUED;
        $searchModel->_dateFilterFrom = $date_filter['from'];
        $searchModel->_dateFilterTo = $date_filter['to'];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'orgModel' => $orgModel,
        ]);
    }

}
