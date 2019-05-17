<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/02/03
 * Time: 1:58 PM
 */

namespace api\modules\v1\controllers;

use api\modules\v1\models\Users;

class UserController extends ActiveController
{
    public function init()
    {
        $this->modelClass = Users::className();
        parent::init();
    }

    public function actionView()
    {
        // default to logged in user
        return $this->getAuthToken()->getIdentity();
    }
}