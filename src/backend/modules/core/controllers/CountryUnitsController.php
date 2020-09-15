<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 1:58 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\auth\Session;
use backend\modules\core\Constants;
use backend\modules\core\forms\UploadOrganizationRefUnits;
use backend\modules\core\models\Country;
use backend\modules\core\models\CountryUnits;
use common\controllers\UploadExcelTrait;
use common\helpers\Str;
use yii\base\InvalidArgumentException;
use yii\helpers\Html;

class CountryUnitsController extends Controller
{
    use UploadExcelTrait;

    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_COUNTRY;
        $this->resourceLabel = 'Country';
    }

    public function setResourceLabel(Country $country, $level)
    {
        switch ($level) {
            case CountryUnits::LEVEL_REGION:
                $this->resourceLabel = Html::encode($country->unit1_name);
                break;
            case CountryUnits::LEVEL_DISTRICT:
                $this->resourceLabel = Html::encode($country->unit2_name);
                break;
            case CountryUnits::LEVEL_WARD:
                $this->resourceLabel = Html::encode($country->unit3_name);
                break;
            case CountryUnits::LEVEL_VILLAGE:
                $this->resourceLabel = Html::encode($country->unit4_name);
                break;
            default:
                throw new InvalidArgumentException();
        }
        $this->pageTitle = null;
        $this->setDefaultPageTitles($this->action);
    }

    public function actionIndex($country_id, $level)
    {
        $countryModel = Country::loadModel(['uuid' => $country_id]);
        $this->setResourceLabel($countryModel, $level);
        $searchModel = CountryUnits::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
        ]);
        $searchModel->is_active = 1;
        $searchModel->country_id = $countryModel->id;
        $searchModel->level = $level;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'countryModel' => $countryModel,
        ]);
    }

    public function actionCreate($country_id, $level)
    {
        $countryModel = Country::loadModel(['uuid' => $country_id]);
        $this->setResourceLabel($countryModel, $level);
        $model = new CountryUnits(['country_id' => $countryModel->id, 'level' => $level]);
        return $model->simpleAjaxSave('_form', 'country/view', ['id' => $countryModel->uuid]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->setResourceLabel($model->country, $model->level);
        return $model->simpleAjaxSave('_form', 'country/view', ['id' => $model->country->uuid]);
    }

    /**
     * @param $id
     * @return CountryUnits
     * @throws \yii\web\NotFoundHttpException
     */
    protected function loadModel($id)
    {
        if (is_string($id) && !is_numeric($id)) {
            $model = CountryUnits::loadModel(['uuid' => $id]);
        } else {
            $model = CountryUnits::loadModel($id);
        }

        return $model;
    }

    public function actionGetList($level, $country_id = null, $parent_id = null, $placeholder = false)
    {
        if ($level == CountryUnits::LEVEL_REGION) {
            $data = CountryUnits::getListData('id', 'name', $placeholder, ['country_id' => $country_id, 'level' => $level]);
        } else {
            if (Str::contains($parent_id, ',')){
                $parent_id = explode(',', $parent_id);
            }
            $data = CountryUnits::getListData('id', 'name', $placeholder, ['parent_id' => $parent_id, 'level' => $level]);
        }
        return json_encode($data);
    }

    /**
     * @param $level
     * @param null $country_id
     * @return bool|false|string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */

    public function actionUpload($level, $country_id = null)
    {
        if (Session::isCountry()) {
            $country_id = Session::getCountryId();
        }
        $countryModel = Country::loadModel($country_id);
        $this->setResourceLabel($countryModel, $level);
        $this->hasPrivilege(Acl::ACTION_CREATE);

        $form = new UploadOrganizationRefUnits(CountryUnits::class, ['country_id' => $countryModel->id, 'level' => $level]);
        $resp = $this->uploadExcelConsole($form, 'index', ['country_id' => $countryModel->uuid, 'level' => $level]);
        if ($resp !== false) {
            return $resp;
        }

        return $this->render('upload', [
            'model' => $form,
            'countryModel' => $countryModel,
        ]);
    }

    /**
     * @param $level
     * @param $country_id
     * @return bool|string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUploadPreview($level, $country_id)
    {
        $form = new UploadOrganizationRefUnits(CountryUnits::class, ['level' => $level, 'country_id' => $country_id]);
        return $form->previewAction();
    }
}