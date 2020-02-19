<?php

namespace backend\modules\help\controllers;


use backend\controllers\BackendController;
use backend\modules\help\Constants;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class Controller extends BackendController
{
    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();

        if (empty($this->activeMenu)) {
            $this->activeMenu = Constants::MENU_HELP;
        }
        if (empty($this->resource)) {
            $this->resource = Constants::RES_HELP;
        }
        if (empty($this->helpModuleName)) {
            $this->helpModuleName = Constants::HELP_MODULE_NAME;
        }
        $this->enableHelpLink = false;
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
                            'search',
                            'manual',
                            'module',
                            'content',
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'upload-image',
                            'read',
                            'manual',
                            'api-doc',
                            'db-doc',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post', 'upload-image'],
                ],
            ],
        ];
    }
}