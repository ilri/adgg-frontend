<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-01
 * Time: 6:43 PM
 */

namespace common\controllers;


use common\excel\ExcelUploadForm;
use common\helpers\Lang;
use common\helpers\Url;
use Yii;
use yii\helpers\ArrayHelper;

trait UploadExcelTrait
{
    /**
     * @param ExcelUploadForm $form
     * @param string $redirectUrlRoute
     * @param array $redirectUrlParams
     * @return false|string
     */
    public function uploadExcelWeb(ExcelUploadForm $form, $redirectUrlRoute = 'index', $redirectUrlParams = [])
    {
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate() && $form->addToExcelQueue()) {
                //process the file
                $form->saveExcelData();
                if (count($form->getSavedRows()) > 0) {
                    $successMsg = Lang::t('{n} rows successfully uploaded.', ['n' => count($form->getSavedRows())]);
                    Yii::$app->session->setFlash('success', $successMsg);
                }
                if (count($form->getFailedRows()) > 0) {
                    $warningMsg = '<p>' . Lang::t('{n} rows could could not be saved.', ['n' => count($form->getFailedRows())]) . '</p>';
                    $warningMsg .= '<ul style="max-height: 200px;overflow: auto">';
                    foreach ($form->getFailedRows() as $n => $message) {
                        $warningMsg .= '<li>' . $message . '</li>';
                    }
                    $warningMsg .= '</ul>';
                    Yii::$app->session->setFlash('warning', $warningMsg);
                }
                $redirectUrl = ArrayHelper::merge([$redirectUrlRoute], $redirectUrlParams);
                return json_encode(['success' => true, 'savedRows' => $form->getSavedRows(), 'failedRows' => $form->getFailedRows(), 'redirectUrl' => Url::to($redirectUrl)]);
            } else {
                return json_encode(['success' => false, 'message' => $form->getErrors()]);
            }
        }
        return false;
    }

    public function uploadExcelConsole(ExcelUploadForm $form, $redirectUrlRoute = 'index', $redirectUrlParams = [])
    {
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate() && $form->addToExcelQueue()) {
                Yii::$app->session->setFlash('success', Lang::t('File queued for processing. You will get notification once the file processing is completed.'));
                $redirectUrl = ArrayHelper::merge([$redirectUrlRoute], $redirectUrlParams);
                return json_encode(['success' => true, 'redirectUrl' => Url::to($redirectUrl)]);
            } else {
                return json_encode(['success' => false, 'message' => $form->getErrors()]);
            }
        }

        return false;
    }
}