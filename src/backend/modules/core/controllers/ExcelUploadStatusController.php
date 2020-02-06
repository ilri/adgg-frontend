<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-15
 * Time: 5:04 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\auth\Acl;
use backend\modules\core\Constants;
use backend\modules\core\models\ExcelImport;
use common\helpers\FileManager;
use common\helpers\Str;

class ExcelUploadStatusController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_EXCEL_UPLOAD_STATUS;
        $this->resourceLabel = 'Excel Upload Status';
    }


    public function actionIndex($id = null)
    {
        $this->hasPrivilege(Acl::ACTION_VIEW);
        $condition = '';
        $params = [];
        $searchModel = ExcelImport::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'with' => [],
        ]);

        if (is_numeric($id)) {
            $searchModel->id = $id;
        } elseif (is_string($id)) {
            $searchModel->uuid = $id;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDownloadFile($id)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);
        $model = ExcelImport::loadModel($id);
        FileManager::downloadFile($model->getFilePath(), Str::removeWhitespace($model->file_name));
    }

    public function actionDownloadError($id)
    {
        $model = ExcelImport::loadModel($id);
        $fileName = Str::removeWhitespace($model->error_csv);
        FileManager::downloadFile($model->getCSVErrorFilePath(), $fileName, 'application/csv');
    }

    public function actionDelete($id)
    {
        $this->hasPrivilege(Acl::ACTION_DELETE);
        return ExcelImport::softDelete($id);
    }
}