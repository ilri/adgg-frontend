<?php

namespace api\modules\v1\forms;

use api\modules\v1\models\Users;
use common\models\Model;
use Yii;

class ChangePassword extends Model
{
    public $username;

    public $old_password;

    public $new_password;

    public function rules()
    {
        return [
            [['old_password', 'new_password'], 'required'],
            Users::passwordStrengthRule('new_password'),
        ];
    }
}