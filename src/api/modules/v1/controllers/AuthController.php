<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/09/26
 * Time: 8:33 PM
 */

namespace api\modules\v1\controllers;

use api\controllers\Controller;
use api\controllers\JwtAuthTrait;
use api\modules\v1\forms\ChangePassword;
use api\modules\v1\forms\LoginForm;
use api\modules\v1\forms\ProvideEmail;
use api\modules\v1\forms\ResetPassword;
use api\modules\v1\models\User;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class AuthController extends Controller
{
    use JwtAuthTrait;

    public function getUnAuthenticatedActions()
    {
        return ['login', 'beginResetPassword', 'finishResetPassword'];
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->getBodyParams(), '');
        if (!$model->validate()) {
            // validation errs
            return $model;
        }
        $password = $model->password;

        /* @var $user User */
        $user = User::findByUsername($model->username);
        if ($user === null || !$user->validatePassword($password)) {
            throw new ForbiddenHttpException("Invalid credentials.");
        }

        return [
            'token' => $user->getToken(),
        ];
    }

    public function actionChangePassword()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        $model = new ChangePassword();
        $model->username = $user->username;
        if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->validate()) {
            if ($user->validatePassword($model->old_password)) {
                $user->setPasswordHash($model->new_password);
                $user->require_password_change = false;
                $user->save(false);
                return $this->asJson([
                    'message' => 'password changed successfully',
                    'token' => $user->getToken()
                ]);
            } else {
                $model->addError('old_password', 'The password is incorrect.');
            }
        }
        return $model;
    }

    public function actionBeginResetPassword()
    {
        $model = new ProvideEmail();
        if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->validate()) {
            // find user by email
            if ($user = User::findByEmail($model->email)) {
                $user->password_reset_token = $user->generatePasswordResetToken();
                $user->save(false);

                return $this->asJson([
                    'message' => 'Reset link sent successfully to ' . $model->email,
                ]);
            }
            throw new NotFoundHttpException('User with email ' . $model->email . ' not found.');
        }
        return $model;
    }

    public function actionCompleteResetPassword()
    {
        $form = new ResetPassword();
        $token = Yii::$app->request->get('token');
        $form->load(Yii::$app->request->getBodyParams(), '');

        if ($form->validate()) {

            $user = User::findByPasswordResetToken($token);
            if ($user !== null) {
                $status = User::isPasswordResetTokenValid($token);
                if ($status) {
                    $user->setPasswordHash($form->password);
                    $user->password_reset_token = null;
                    $user->save(false);

                    return ['message' => 'password reset.'];
                } else {
                    throw new NotFoundHttpException('The token has expired.');
                }
            }
            throw new NotFoundHttpException('The token was not found.');
        }
        return $form;
    }
}