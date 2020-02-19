<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2015/12/07
 * Time: 1:46 AM
 */

namespace backend\modules\conf\controllers;

use backend\modules\auth\Session;
use backend\modules\conf\models\NumberingFormat;

class NumberFormatController extends DevController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Number Format';
    }

    public function actionIndex($country_id = null)
    {
        if (Session::isOrganizationRef()) {
            $country_id = Session::getCountryId();
        }
        $condition = ['country_id' => $country_id];
        $searchModel = NumberingFormat::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'condition' => $condition,
        ]);
        $searchModel->is_active = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new NumberingFormat();
        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $model = NumberingFormat::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        NumberingFormat::softDelete($id);
    }
}