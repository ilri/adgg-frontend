<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-22
 * Time: 9:03 PM
 */

namespace api\controllers;


use sizeg\jwt\JwtHttpBearerAuth;

trait JwtAuthTrait
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => array_merge($this->getUnAuthenticatedActions(), ['OPTIONS']),
        ];

        return $behaviors;
    }

    /**
     * Authenticated actions. By default, all main API endpoints will require authentication
     *
     * @return array
     */
    protected function getAuthenticatedActions()
    {
        return null;
    }

    /**
     * Unauthenticated actions
     *
     * @return array
     */
    protected function getUnAuthenticatedActions()
    {
        return [];
    }
}