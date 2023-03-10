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
            $this->activeMenu = Constants::MENU_ORGANIZATION_REF;
        if (empty($this->resource))
            $this->resource = Constants::RES_COUNTRY;

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
                            'upload-preview',
                            'approve',
                            'get-list',
                            'quick-create',
                            'typeahead-list',
                            'change-status',
                            'process',
                            'download-file',
                            'download-error',
                            'event-list',
                            'upload-metadata',
                            'view-metadata',
                            'upload-metadata-preview',
                            'excel-update',
                            'excel-update-preview',
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
                    'process' => ['post'],
                ],
            ],
        ];
    }
}