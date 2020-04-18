<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-04-19
 * Time: 12:04 PM
 */

namespace backend\modules\dashboard\controllers;


use backend\modules\core\models\Country;

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
        return $this->render('index', [
            'filterOptions' => []
        ]);
    }

    public function actionLoadChart($name)
    {
        return $this->renderAjax('partials/'. $name, [
            'filterOptions' => [],
        ]);
    }

}