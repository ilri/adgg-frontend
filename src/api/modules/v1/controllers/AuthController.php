<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/09/26
 * Time: 8:33 PM
 */

namespace api\modules\v1\controllers;

use api\controllers\AuthTrait;
use api\controllers\Controller;
use api\controllers\SendsResponse;
use api\modules\v1\Config;
use api\modules\v1\forms\ChangePassword;
use api\modules\v1\forms\LoginForm;
use api\modules\v1\forms\ProvideEmail;
use api\modules\v1\forms\ResetPassword;
use api\modules\v1\GoogleAccountChecker;
use api\modules\v1\models\Users;
use api\modules\v1\pojo\ResponseObject;
use backend\modules\auth\models\UserLevels;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class AuthController extends Controller
{
    use AuthTrait, GoogleAccountChecker, SendsResponse;


    public function getUnAuthenticatedActions()
    {
        return ['authorize', 'beginResetPassword', 'finishResetPassword'];
    }

    /**
     * @return LoginForm|ResponseObject
     * @throws ForbiddenHttpException
     * @throws HttpException
     */
    public function actionAuthorize()
    {
        $model = new LoginForm();
        if (!$model->load(Yii::$app->request->getBodyParams(), '') || !$model->validate()) {
            // validation errs
            return $model;
        }
        $username = $model->username;
        $password = $model->password;

        /* @var $user Users */
        $user = Users::findByUsername($username);
        if ($user == null) {
            throw new ForbiddenHttpException("Invalid credentials.");
        }
        if (!$user->validatePassword($password)) {
            throw new ForbiddenHttpException('Invalid credentials.');
        }

        return $this->sendMessage([
            'token' => $user->getToken(),
        ]);
    }

    /**
     * Change password
     *
     * @return ChangePassword|ResponseObject
     */
    public function actionChangePassword()
    {
        /* @var $user Users*/
        $user = $this->getAuthToken()->getIdentity();
        $model = new ChangePassword();
        $model->username = $user->username;
        if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->validate()) {
            if ($user->validatePassword($model->old_password)) {
                $user->setPasswordHash($model->new_password);
                $user->require_password_change = false;
                $user->save(false);
                return $this->sendMessage(['message' => 'password changed successfully', 'token' => $user->getToken()]);
            } else {
                $model->addError('old_password', 'The password is incorrect.');
            }
        }
        return $model;
    }

    /**
     * Send a password reset link to a user's email address
     *
     * @throws NotFoundHttpException
     */
    public function actionBeginResetPassword()
    {
        $model = new ProvideEmail();
        if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->validate()) {
            // find user by email
            if ($user = Users::findByEmail($model->email)) {
                $user->password_reset_token = $user->generatePasswordResetToken();
                $user->save(false);

                return $this->sendMessage(['message' => 'Reset link sent successfully to ' . $model->email]);
            }
            throw new NotFoundHttpException('User with email ' . $model->email . ' not found.');
        }
        return $model;
    }

    /**
     * Complete password reset
     * @return ResetPassword|ResponseObject
     * @throws NotFoundHttpException
     */
    public function actionCompleteResetPassword()
    {
        $form = new ResetPassword();
        $token = Yii::$app->request->get('token');
        $form->load(Yii::$app->request->getBodyParams(), '');

        if ($form->validate()) {

            $user = Users::findByPasswordResetToken($token);
            if ($user !== null) {
                $status = Users::isPasswordResetTokenValid($token);
                if ($status) {
                    $user->setPasswordHash($form->password);
                    $user->password_reset_token = null;
                    $user->save(false);
                    return $this->sendMessage(['message' => 'password reset.']);
                } else {
                    throw new NotFoundHttpException('The token has expired.');
                }
            }
            throw new NotFoundHttpException('The token was not found.');
        }
        return $form;
    }
}