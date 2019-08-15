<?php
/**
 * Created by PhpStorm.
 * User: fred
 * Date: 25/10/18
 * Time: 18:28
 */

namespace backend\modules\conf\controllers;


use backend\modules\conf\Constants;
use backend\modules\conf\models\SmsTemplate;
use backend\modules\conf\settings\SmsSettings;

class SmsTemplateController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->activeSubMenu = Constants::SUBMENU_SMS;
        $this->resourceLabel = 'SMS Template';
    }

    function actions()
    {
        return [
            'settings' => [
                'class' => \yii2mod\settings\actions\SettingsAction::class,
                'modelClass' => SmsSettings::class,
                'sectionName' => SmsSettings::SECTION_SMS,
                'view' => 'settings',
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = SmsTemplate::searchModel([
            'defaultOrder' => ['code' => SORT_ASC]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new SmsTemplate([]);
        return $model->simpleAjaxSave();
    }

    public function actionUpdate($id)
    {
        $model = SmsTemplate::loadModel($id);
        return $model->simpleAjaxSave();
    }

    public function actionDelete($id)
    {
        SmsTemplate::softDelete($id);
    }
}