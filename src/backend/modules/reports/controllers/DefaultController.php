<?php

namespace backend\modules\reports\controllers;

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
    }

    public function actionIndex()
    {
        return $this->render('index',[
        ]);
    }
}
