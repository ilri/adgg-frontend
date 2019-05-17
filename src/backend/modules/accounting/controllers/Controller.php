<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-12-19 15:15
 * Time: 15:15
 */

namespace backend\modules\accounting\controllers;


use backend\controllers\BackendController;
use backend\modules\accounting\Constants;
use backend\modules\auth\Session;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

class Controller extends BackendController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->activeMenu))
            $this->activeMenu = Constants::MENU_ACCOUNTING;
        if (empty($this->resource))
            $this->resource = Constants::RES_ACCOUNTING;

        $this->enableDefaultAcl = true;
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!Session::isOrganization()) {
                throw new ForbiddenHttpException();
            }

            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'test',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'download',
                            'upload',
                            'approve',
                            'get-list',
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