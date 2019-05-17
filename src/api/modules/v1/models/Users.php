<?php

namespace api\modules\v1\models;

use msheng\JWT\UserTrait;

class Users extends \backend\modules\auth\models\Users
{
    use UserTrait;

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->getJWT($this->getExtraPayload());
    }

    /**
     * @return array
     */
    protected function getExtraPayload()
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'id' => $this->id,
            'role_id' => $this->role_id,
            'level_id' => $this->level_id,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getAlgo()
    {
        return 'HS512';
    }
}