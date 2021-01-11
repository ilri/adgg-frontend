<?php
/**
 * Created by PhpStorm.
 * @author Fred <mconyango@gmail.com>
 * Date: 2018-06-21
 * Time: 17:09
 */

namespace common\models;


use common\helpers\Lang;
use common\helpers\Url;
use Yii;
use yii\db\Exception;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

trait ControllerActionTrait
{
    /**
     * Performs simple ajax save
     * @param string $view
     * @param string $redirect_route
     * @param array $redirect_params
     * @param string|null $success_msg
     * @param bool $forceRedirect
     * @param string $idParam
     * @return bool|string
     * @throws \yii\base\ExitException
     */
    public function simpleAjaxSave($view = '_form', $redirect_route = 'index', $redirect_params = [], $success_msg = null, $forceRedirect = false, $idParam = 'id')
    {
        if (empty($success_msg))
            $success_msg = Lang::t('SUCCESS_MESSAGE');

        if ($this->ajaxValidate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $this->save(false);
                $transaction->commit();

                $primary_key_field = static::getPrimaryKeyColumn();
                $redirect_url = Url::to(array_merge([$redirect_route, $idParam => $this->{$primary_key_field}], (array)$redirect_params));
                return Json::encode(['success' => true, 'message' => $success_msg, 'redirectUrl' => Url::getReturnUrl($redirect_url), 'forceRedirect' => $forceRedirect]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::debug($e->getTrace());
                return Json::encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        return Yii::$app->controller->renderAjax($view, [
            'model' => $this,
        ]);
    }

    /**
     * Performs simple non-ajax save
     * @param string $view
     * @param string $redirect_action
     * @param string|null $success_msg
     * @return mixed
     * @throws Exception
     */
    public function simpleSave($view = 'create', $redirect_action = 'index', $success_msg = null)
    {
        if (empty($success_msg))
            $success_msg = Lang::t('SUCCESS_MESSAGE');

        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $this->save(false);
                $transaction->commit();

                Yii::$app->session->setFlash('success', $success_msg);
                if ($redirect_action === 'index' || $redirect_action === 'create') {
                    $redirect_url = Url::to([$redirect_action]);
                } else {
                    $primary_key_field = static::getPrimaryKeyColumn();
                    $redirect_url = Url::to([$redirect_action, 'id' => $this->{$primary_key_field}]);
                }

                return Yii::$app->controller->redirect(Url::getReturnUrl($redirect_url));
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        }

        return Yii::$app->controller->render($view, [
            'model' => $this,
        ]);
    }

    /**
     * @param array $attributes
     * @return bool
     * @throws \yii\base\ExitException
     */
    public function ajaxValidate($attributes = null)
    {
        $posted = $this->load(Yii::$app->request->post());
        $validationSuccess = false;
        if (Yii::$app->request->isAjax && $posted) {
            $validationResults = ActiveForm::validate($this, $attributes);
            if (Yii::$app->request->post('ajax')) {
                if (empty($validationResults)) {
                    echo Json::encode(['success' => true]);
                } else {
                    echo Json::encode($validationResults);
                }

                Yii::$app->end();
            }
            if (empty($validationResults)) {
                $validationSuccess = true;
            } else {
                echo Json::encode($validationResults);
                Yii::$app->end();
            }
        }

        return $posted && $validationSuccess;
    }
}