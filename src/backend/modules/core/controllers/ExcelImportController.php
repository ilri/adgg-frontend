<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-15
 * Time: 5:04 AM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\Constants;
use backend\modules\core\models\ExcelImport;
use common\helpers\FileManager;
use common\helpers\Str;

class ExcelImportController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_EXCEL_FILE;
        $this->resourceLabel = 'Excel Upload';
    }


    public function actionIndex()
    {
        $condition = '';
        $params = [];
        $searchModel = ExcelImport::searchModel([
            'defaultOrder' => ['id' => SORT_DESC],
            'condition' => $condition,
            'params' => $params,
            'with' => [],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDownloadFile($id)
    {
        $model = ExcelImport::loadModel($id);
        FileManager::downloadFile($model->getFilePath(), Str::removeWhitespace($model->file_name));
    }

    public function actionDownloadError($id)
    {
        $model = ExcelImport::loadModel($id);

        $fileName = Str::removeWhitespace($model->error_csv);
        header('HTTP/1.1 200 OK');
        header('Cache-Control: no-cache, must-revalidate');
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$fileName");
        readfile($model->getCSVErrorFilePath());
        \Yii::$app->end();
    }
}