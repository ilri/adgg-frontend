<?php

namespace backend\modules\reports\controllers;

use backend\modules\auth\Acl;
use common\helpers\DateUtils;

/**
 * Default controller for the `reports` module
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resource = \backend\modules\core\Constants::RES_REPORT_BUILDER;
        $this->hasPrivilege(Acl::ACTION_CREATE);
    }

    public function actionIndex($country_id)
    {
        return $this->render('index',[
            'country_id' => $country_id,
        ]);
    }

    public function actionView($type, $country_id){
        $from = null; $to = null;
        $date_filter = DateUtils::getDateFilterParams($from, $to, 'created_at', false, true);
        $condition = $date_filter['condition'];
        $params = [];

        return $this->render('view',[
            'type' => $type,
            'country_id' => $country_id,
            'filterOptions' => [
                'region_id' => null,
                'district_id' => null,
                'ward_id' => null,
                'village_id' => null,
                'dateFilterFrom' => $date_filter['from'],
                'dateFilterTo' => $date_filter['to'],
            ],
        ]);
    }
}
