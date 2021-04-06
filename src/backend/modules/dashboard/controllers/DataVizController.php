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
use backend\modules\dashboard\models\DataViz;
use common\helpers\Str;
use common\helpers\Url;
use Yii;
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
        $str = 'platformuniqueid/breeding_syncanimalplatformuniqueid';
        $substr = Str::str_lreplace('platformuniqueid', 'code', $str);
        dd($str, $substr);

        $country_id = \Yii::$app->request->get('country_id');
        $org_id = \Yii::$app->request->get('org_id');
        if (Session::isPrivilegedAdmin()) {
            $view = 'index';
            if ($country_id !== null || $org_id !== null) {
                $view = 'country';
            }
            return $this->render($view, [
                'filterOptions' => [
                    'country_id' => $country_id,
                    'org_id' => $org_id,
                ]
            ]);
        } elseif (Session::isCountryUser()) {
            $country_id = Session::getCountryId();
            $org_id = Session::getOrgId();
            return $this->render('country', [
                'filterOptions' => [
                    'country_id' => $country_id,
                    'org_id' => $org_id,
                ]
            ]);
        } elseif (Session::isOrganizationUser()) {
            return $this->redirect(Url::to(['/dashboard/default']));
        } else {
            throw new ForbiddenHttpException();
        }
    }

    public function actionLoadChart($name)
    {
        $country_id = Yii::$app->request->get('country_id');
        $org_id = Yii::$app->request->get('org_id');
        $is_country = Yii::$app->request->get('is_country', 0);
        $view = 'partials/' . $name;
        if ($is_country) {
            $view = 'country/' . $name;
        }
        return $this->renderAjax($view, [
            'country_id' => $country_id,
            'filterOptions' => [
                'country_id' => $country_id,
                'org_id' => $org_id,
            ],
        ]);
    }

    public function actionGetAges($animal_type, $placeholder = false)
    {
        if ($animal_type == DataViz::ANIMAL_TYPE_COW) {
            $data = DataViz::ageRangeCows($placeholder);
        } else {
            $data = DataViz::ageRangeCalves($placeholder);
        }
        return json_encode($data);
    }

}