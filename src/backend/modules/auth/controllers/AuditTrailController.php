<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/05/05
 * Time: 2:42 PM
 */

namespace backend\modules\auth\controllers;


use backend\modules\auth\Constants;
use backend\modules\auth\models\AuditTrail;
use backend\modules\auth\Session;
use backend\modules\core\models\Country;
use common\helpers\DateUtils;

class AuditTrailController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->resourceLabel = 'Audit Trail';
        $this->activeSubMenu = Constants::SUBMENU_AUDIT_TRAIL;
    }

    public function actionIndex($user_id = null, $country_id = null, $action = null, $from = null, $to = null)
    {
        $countryModel = null;
        if (Session::isCountry()) {
            $country_id = Session::getCountryId();
        }
        if (!empty($country_id)) {
            $countryModel = Country::loadModel($country_id);
        }
        $date_filter = DateUtils::getDateFilterParams($from, $to, 'created_at', true, true);
        $condition = $date_filter['condition'];
        $params = [];
        $searchModel = AuditTrail::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'with' => ['user'],
        ]);
        $searchModel->user_id = $user_id;
        $searchModel->country_id = $country_id;
        $searchModel->action = $action;
        $searchModel->_dateFilterFrom = $date_filter['from'];
        $searchModel->_dateFilterTo = $date_filter['to'];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'countryModel' => $countryModel,
        ]);
    }

    public function actionView($id)
    {
        $model = AuditTrail::loadModel($id);

        return $this->renderPartial('view', [
            'model' => $model,
        ]);
    }

}