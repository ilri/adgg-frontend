<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-04-19
 * Time: 12:04 PM
 */

namespace backend\modules\dashboard\controllers;


use backend\modules\auth\Session;
use backend\modules\core\models\Country;
use common\helpers\Url;
use yii\web\ForbiddenHttpException;

class DataVizController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()) {
            return $this->render('index', [
                'filterOptions' => []
            ]);
        } else {
            throw new ForbiddenHttpException();
        }
        //return $this->redirect(Url::to(['/dashboard/default']));
    }

    public function actionLoadChart($name)
    {
        return $this->renderAjax('partials/'. $name, [
            'filterOptions' => [],
        ]);
    }

}