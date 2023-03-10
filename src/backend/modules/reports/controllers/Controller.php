<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/05/16
 * Time: 6:19 PM
 */

namespace backend\modules\reports\controllers;


use backend\controllers\BackendController;
use backend\modules\reports\Constants;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class Controller extends BackendController
{
    public function init()
    {
        parent::init();
        if (empty($this->activeMenu))
            $this->activeMenu = Constants::MENU_REPORTS;
        if (empty($this->resource))
            $this->resource = Constants::RES_REPORTS;

        $this->enableDefaultAcl = true;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'generate-query',
                            'save-report',
                            'download-file',
                            'requeue',
                            'generate',
                            'process',
                            'errors',

                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
}