<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-04
 * Time: 5:14 PM
 */

namespace api\modules\v1\controllers;


use backend\modules\core\models\OdkJsonQueue;
use common\helpers\FileManager;
use common\helpers\Lang;
use yii\web\UploadedFile;

class OdkJsonController extends ActiveController
{
    public function init()
    {
        $this->modelClass = OdkJsonQueue::class;
        parent::init();
    }

    public function getUnAuthenticatedActions()
    {
        return ['create'];
    }

    public function actionIndex()
    {
        $searchModel = OdkJsonQueue::searchModel(['pageSize' => 100, 'defaultOrder' => ['id' => SORT_ASC]]);

        return $searchModel->search();
    }

    public function actionCreate()
    {
        $model = new OdkJsonQueue([]);
        $model->setScenario(OdkJsonQueue::SCENARIO_API_PUSH);
        $model->jsonFile = UploadedFile::getInstanceByName('json_file');
        if (null === $model->jsonFile) {
            $model->addError('jsonFile', Lang::t('Please upload a JSON file'));
            return $model;
        }
        $model->tmp_file = FileManager::getTempDir() . DIRECTORY_SEPARATOR . $model->jsonFile->name;
        $model->jsonFile->saveAs($model->tmp_file);
        $model->jsonFile = null;

        if ($model->save()) {
            return $this->sendMessage([
                'status' => 200,
                'statusMessage' => 'success',
                'description' => Lang::t('JSON File queued. We will notify you when the file is processed.'),
            ]);
        }

        return $model;
    }
}