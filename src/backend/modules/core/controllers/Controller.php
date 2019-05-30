<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-04-19 12:59 PM
 */

namespace backend\modules\core\controllers;


use backend\controllers\BackendController;
use backend\modules\core\Constants;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class Controller extends BackendController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->activeMenu))
            $this->activeMenu = Constants::MENU_ORGANIZATION;
        if (empty($this->resource))
            $this->resource = Constants::RES_MEMBERS;

        $this->enableDefaultAcl = true;
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
                            'quick-create',
                            'typeahead-list',
                            'change-status',
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